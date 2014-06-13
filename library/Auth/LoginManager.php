<?php
/*
* LoginManager
*
* Verify's the users login credentials and
checks the users role against the ACL for access rights.
*
* @return Access Privileges
*/
class Auth_LoginManager {

	private $dbAdapter;
	private $authAdapter;
	
	/**
	* @return mixed
	*/
	public function __construct() {
	
		// Get a reference to the singleton instance of Zend_Auth
		$this->auth = Zend_Auth::getInstance();
	}
	
	// test
	
	/**
	* @return void
	*/
	public function test() {
		return "Success! Test Completed Normally";
	}
	
	/**
	*
	* Authenticates the user
	*
	* @todo add routine to verify using SSO
    * @param mixed $user
	* 	
	* @return mixed
	*
	*/
	public function verifyUser($user) {
		
		$userRole='';
		// Configure the instance with constructor parameters…
		$authAdapter = new Zend_Auth_Adapter_DbTable();//on prend le table adapter par défaut
		$authAdapter->setTableName('flux_uti')
            ->setIdentityColumn('login')
            ->setCredentialColumn('mdp')
			//->setCredentialTreatment('MD5(?)')
            //->setCredentialTreatment('MD5(CONCAT(?, mdp_sel))')
			;
		$usr=htmlspecialchars($user->username);
		$pwd=htmlspecialchars($user->password);
		if($usr == ''){
			$authAdapter
				->setIdentity('invité')
				->setCredential('invité');
		}else{
			$authAdapter
				->setIdentity($usr)
				->setCredential($pwd);
		}
		$result = $authAdapter->authenticate();
	
		switch ($result->getCode()) {
	
			case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
				$userRole = "invité";
				break;
		
			case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
				$userRole = "invité";
				return "FAILURE_CREDENTIAL_INVALID";
				break;
	
			case Zend_Auth_Result::FAILURE:
				$userRole = 'invité';
				break;
	
			case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
				$userRole = 'invité';
				break;
	
			case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
				$userRole = 'invité';
				break;
		
			case Zend_Auth_Result::SUCCESS:
	
				// We need to return the authenticated users role, this will be passed into the Zend_Acl
				// getResultRowObject returns a stdClass object so we need to dereference the role in this manner.
				$r=$authAdapter->getResultRowObject(array('uti_id','role'));
				$userRole = $r->role;
				$userId = $r->uti_id;
				break;
	
			default:
				return "Internal Error! If this problem persist, please contact your network administrator";
				break;
		}
	
		// Set up the ACL (Access Control List)
		$acl = new Zend_Acl();
		// Add groups to the Role registry using Zend_Acl_Role
		// invité does not inherit access controls.
		// Order matters here, we go from the most	restricted to the least restricted
		$dbRole = new Model_DbTable_Flux_Roles();
		$rs = $dbRole->getAll();
		
		foreach ($rs as $r){
			/*problème d'héritage
			if($r['inherit']!=""){
				$roleHerite = $acl->getRole($r['inherit']);
				$acl->addRole(new Zend_Acl_Role($r['lib'],$roleHerite));							
			}else{
				$acl->addRole(new Zend_Acl_Role($r['lib']));							
			}
			*/
			$acl->addRole(new Zend_Acl_Role($r['lib']));							
			$res = json_decode($r['params']);
			if($res){
				// application des droits
				$acl->allow($r['lib'], null, $res);
				foreach ($res as $r){
					if(!$acl->has($r))$acl->addResource(new Zend_Acl_Resource($r));
				}				
			}
		}
		
		//création du tableau des droits
		$userRolePrivs = array();		
		$userRolePrivs["idUti"] = $userId;
		$userRolePrivs["role"] = $userRole;
		$userRolePrivs["login"] = $user->username;

		//ajoute les autorisations liées au role
		$rs = $acl->getResources();
		foreach ($rs as $r){
			$userRolePrivs[$r] = $acl->isAllowed($userRole, null, $r);			
		}

		//ajoute les droits sur les oeuvres
		if($userId){
			$dbUti = new Model_DbTable_Flux_Uti();
			$userRolePrivs["oeuvres"] = $dbUti->getOeuvres($userId, $userRole);
			$userRolePrivs["dicos"] = $dbUti->getDicos($userId, $userRole);			
		}
		
		return $userRolePrivs;
	}
}