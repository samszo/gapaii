<?php
/**
 * Ce fichier contient la classe Flux_Uti.
 *
 * @copyright  2011 Samuel Szoniecky
 * @license    "New" BSD License
*/


/**
 * Classe ORM qui représente la table 'flux_Uti'.
 *
 * @copyright  2011 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Flux_Uti extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'flux_uti';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'uti_id';

    protected $_dependentTables = array(
       "Model_DbTable_Flux_ActiUti"
       );
    
    /**
     * Vérifie si une entrée Flux_Uti existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('uti_id'));
		foreach($data as $k=>$v){
			if($k!="date_inscription" && $k!="mdp")
				$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->uti_id; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée Flux_Uti.
     *
     * @param array $data
     * @param boolean $existe
     *  
     * @return integer
     */
    public function ajouter($data, $existe=true)
    {
    	$id=false;
    	if($existe)$id = $this->existe($data);
    	if(!$id){
    		if(!isset($data["date_inscription"]))$data["date_inscription"]= new Zend_Db_Expr('NOW()');
    		if(!isset($data["ip_inscription"]))$data["ip_inscription"]= $_SERVER['REMOTE_ADDR'];
    		$id = $this->insert($data);
    	}
    	return $id;
    } 
           
    /**
     * Recherche une entrée Flux_Uti avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
        $this->update($data, 'flux_uti.uti_id = ' . $id);
    }
    
    /**
     * Recherche une entrée Flux_Uti avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
		//suppression des données lieés
        $dt = $this->getDependentTables();
        foreach($dt as $t){
        	$dbT = new $t($this->_db);
        	if($t!="Model_DbTable_Flux_UtiUti")
	        	$dbT->delete('uti_id = '.$id);
	        else
	        	$dbT->delete('uti_id_src = '.$id.' OR uti_id_dst = '.$id);
        }            	
    	$this->delete('flux_uti.uti_id = '.$id);
    }
    
    /**
     * Récupère toutes les entrées Flux_Uti avec certains critères
     * de tri, intervalles
     */
    public function getAll($cols=false, $order="login", $limit=0, $from=0)
    {
    	if($cols)
        	$query = $this->select()->from( array("flux_uti" => "flux_uti"), $cols);
    	else 
    		$query = $this->select()->from( array("flux_uti" => "flux_uti") );
                    
        if($order != null)
        {
            $query->order($order);
        }

        if($limit != 0)
        {
            $query->limit($limit, $from);
        }

        return $this->fetchAll($query)->toArray();
    }
    
    /*
     * Recherche une entrée Flux_Uti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $uti_id
     */
    public function findByuti_id($uti_id)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_uti") )                           
                    ->where( "f.uti_id = ?", $uti_id );

        return $this->fetchAll($query)->toArray(); 
    }
    
    /*
     * Recherche des entrées Flux_Uti avec la valeur spécifiée
     * et retourne ces entrées.
     *
     * @param string $ids
     * @param string $champ
     * 
     */
    public function findIn($ids, $champ)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_uti") )                           
                    ->where( "f.".$champ." IN (".$ids.")" );

        return $this->fetchAll($query)->toArray(); 
    }
    
    /*
     * Recherche une entrée Flux_Uti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $login
     */
    public function findByLogin($login)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_uti") )                           
                    ->where( "f.login = ?", $login );
		$arr = $this->fetchAll($query)->toArray();
        return $arr[0]; 
    }
    /*
     * Recherche une entrée Flux_Uti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $maj
     */
    public function findByMaj($maj)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_uti") )                           
                    ->where( "f.maj = ?", $maj );

        return $this->fetchAll($query)->toArray(); 
    }
    /*
     * Recherche une entrée Flux_Uti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $flux
     */
    public function findByFlux($flux)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_uti") )                           
                    ->where( "f.flux = ?", $flux );

        return $this->fetchAll($query)->toArray(); 
    }
    /*
     * Recherche une entrée Flux_Uti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $role
     * @param boolean $img 
     * 
     * @return array
     */
    public function findByRole($role, $img=false)
    {
        $query = $this->select()
			->from( array("u" => "flux_uti"),array("login","uti_id") )                           
            ->where( "u.role = ?", $role );
		if($img){
        	$query->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
        		->joinInner(array('ud' => 'flux_utidoc'),'ud.uti_id = u.uti_id',array())
				->joinInner(array('d' => 'flux_doc'),"d.doc_id = ud.doc_id AND type = 'foaf:img'",array('doc_id','url'));
		}
            
        return $this->fetchAll($query)->toArray(); 
    }
    
    /**
     * renoie les valeur distinct d'une colone
     *
     * @param string $col
     *
     * @return array
     */
    public function getDistinct($col)
    {
        $query = $this->select()
        	->distinct()
   	     	->from( array("f" => "flux_uti"),$col)
   	     	->where($col." !=''");
    	return $this->fetchAll($query)->toArray();        
    } 

    /**
     * renvoie les rôles et les utilisateurs associés
     *
     * @return array
     */
    public function getRolesUtis()
    {
    	$arrRoles = $this->getDistinct("role");
    	$nbRole = count($arrRoles);
    	for ($i = 0; $i < $nbRole; $i++) {
    		$arrUti = $this->findByRole($arrRoles[$i]["role"],true);
    		$arrRoles[$i]['utis'] = $arrUti;
    	}
    	return $arrRoles;        
    } 
    
    /**
     * renvoie la liste des dictionnaires avec le role de l'utilisateur
     *
     * @param int $idUti
     * @param string $role
     * 
     * @return array
     */
    public function getDicos($idUti, $role)
    {
        if($role != ROLE_ADMIN){
	        $query = $this->select()
				->from( array("u" => "flux_uti"),array("uti_id") )                           
	        	->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
	        		->joinInner(array('dur' => 'gen_dicos_utis_roles'),'dur.uti_id = u.uti_id',array('id_dur'))
	        		->joinInner(array('d' => 'gen_dicos'),"d.id_dico = dur.id_dico",array('id_dico','dico'=>'nom'))
	        		->joinInner(array('r' => 'flux_roles'),"r.id_role = dur.id_role",array('id_role','role'=>'lib'))
	        	->where( "u.uti_id = ?", $idUti);
	        return $this->fetchAll($query)->toArray();         	
        }else{
        	$sql ="SELECT id_dico, nom as 'dico', '".ROLE_ADMIN."' as role, '".ID_ROLE_ADMIN."' as id_role
        	FROM gen_dicos";
        	$db = $this->getAdapter()->query($sql);
    		return $db->fetchAll();
        }        		        		            
    } 

    /**
     * ajoute un dictionnaires et un role à l'utilisateur
     *
     * @param array $data
     * 
     * @return int
     */
    public function setDico($data)
    {
    	$dbDUR = new Model_DbTable_Gen_dicosxutisxroles();
    	$id = $dbDUR->ajouter($data);
    	
    	return $id;
    } 
    
    /**
     * supprime un dico et un role d'un utilisateur
     *
     * @param array $idDur
     * 
     */
    public function removeDico($idDur)
    {
    	$dbDUR = new Model_DbTable_Gen_dicosxutisxroles();
    	$id = $dbDUR->remove($idDur);
    } 
    
    /**
     * renvoie la liste des oeuvres avec le role de l'utilisateur
     *
     * @param int $idUti
     * @param string $role
     * 
     * @return array
     */
    public function getOeuvres($idUti, $role)
    {
        if($role != ROLE_ADMIN){
	    	$query = $this->select()
				->from( array("u" => "flux_uti"),array("uti_id") )                           
	        	->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
	        		->joinInner(array('our' => 'gen_oeuvres_utis_roles'),'our.uti_id = u.uti_id',array('id_our'))
	        		->joinInner(array('o' => 'gen_oeuvres'),"o.id_oeu = our.id_oeu",array('id_oeu','oeuvre'=>'lib'))
	        		->joinInner(array('r' => 'flux_roles'),"r.id_role = our.id_role",array('id_role','role'=>'lib'))
	        	->where( "u.uti_id = ?", $idUti);
        	return $this->fetchAll($query)->toArray(); 
        }else{
        	$sql ="SELECT id_oeu, lib as 'oeuvre', '".ROLE_ADMIN."' as role, '".ID_ROLE_ADMIN."' as id_role
        	FROM gen_oeuvres";
        	$db = $this->getAdapter()->query($sql);
    		return $db->fetchAll();
        }
        	
    } 

    /**
     * ajoute une oeuvre et un role à l'utilisateur
     *
     * @param array $data
     * 
     * @return int
     */
    public function setOeuvre($data)
    {
    	$dbOUR = new Model_DbTable_Gen_oeuvresxutisxroles();
    	$id = $dbOUR->ajouter($data);
    	
    	return $id;
    } 
    
    /**
     * supprime une oeuvre et un role d'un utilisateur
     *
     * @param array $idOur
     * 
     */
    public function removeOeuvre($idOur)
    {
    	$dbOUR = new Model_DbTable_Gen_oeuvresxutisxroles();
    	$id = $dbOUR->remove($idOur);
    } 
    
}
