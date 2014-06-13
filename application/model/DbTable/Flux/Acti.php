<?php
/**
 * Ce fichier contient la classe flux_acti.
 *
 * @copyright  2011 Samuel Szoniecky
 * @license    "New" BSD License
*/


/**
 * Classe ORM qui représente la table 'flux_acti'.
 *
 * @copyright  2013 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Flux_Acti extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'flux_acti';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'acti_id';

    protected $_dependentTables = array(
       "Model_DbTable_Flux_ActiUti"
       );    
    
    
    /**
     * Vérifie si une entrée flux_acti existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('acti_id'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->acti_id; else $id=false;
        return $id;
    } 
        
    /**
   	* ajoute des éléments dans l'arbre des activités
    * pour des utilisateurs
	*
    * @param string $actis
    * @param string $idUtis
    * @param string $idOeu
    * @param string $idDico
    * @param int $idItem
    * @param array $data
    *  
    * @return integer
    */
    public function ajoutForUtis($actis, $idUtis, $idOeu, $idDico, $idItem, $data)
    {
    	$dbAU = new Model_DbTable_Flux_ActiUti();
    	$dbOA = new Model_DbTable_Gen_oduxacti();
    	$dbODU = new Model_DbTable_Gen_oeuvresxdicosxutis();
    	$parent = 1;
    	$idUtis = explode(",", $idUtis);
		//création/récupération de l'activité
		$params = array("idDico"=>$idDico,"id"=>$idItem,"data"=>$data);
    	$idActi = $this->ajouter(array("lib"=>$actis,"parent"=>$parent, "params"=>json_encode($params)));
    	foreach ($idUtis as $uti) {
    		//ajouter l'activité à l'utilisateur
    		$dbAU->ajouter(array("acti_id"=>$idActi, "uti_id"=>$uti));
			//récupère la relation entre le dictionnaire l'oeuvre et l'utilisateur
			$idOdu = $dbODU->ajouter($idOeu, $uti, array($idDico));
	    	$dbOA->ajouter(array("acti_id"=>$idActi, "id_odu"=>$idOdu));	 
    	}
    	
    	return $idActi;
    }
    
    /**
     * Ajoute une entrée flux_acti.
     *
     * @param array $data
     * @param boolean $existe
     * @param boolean $rData
     *  
     * @return integer
     */
    public function ajouter($data, $existe=true, $rData=false)
    {
    	$id=false;
    	if($existe)$id = $this->existe($data);
    	if(!$id){
    		//récupère les information du parent
    		$arrP = $this->findByActi_id($data["parent"]);
    		//gestion des hiérarchies gauche droite
    		//http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/
    		//vérifie si le parent à des enfants
    		$arr = $this->findByParent($data["parent"]);
    		if(count($arr)>0){
    			//met à jour les niveaux pour les lieux
    			$sql = 'UPDATE flux_acti SET rgt = rgt + 2 WHERE rgt >'.$arr[0]['rgt'];
    			$stmt = $this->_db->query($sql);
    			$sql = 'UPDATE flux_acti SET lft = lft + 2 WHERE lft >'.$arr[0]['rgt'];
    			$stmt = $this->_db->query($sql);
    			//
    			$data['lft'] = $arr[0]['rgt']+1;
    			$data['rgt'] = $arr[0]['rgt']+2;
    		}else{
    			//vérifie si la base n'est pas vide
    			if(count($arrP)==0){
    				$data['lft'] = 1;
    				$data['rgt'] = 2;
    			}else{
	    			//met à jour les niveaux pour les lieux
	    			$sql = 'UPDATE flux_acti SET rgt = rgt + 2 WHERE rgt >'.$arrP[0]['lft'];
	    			$stmt = $this->_db->query($sql);
	    			$sql = 'UPDATE flux_acti SET lft = lft + 2 WHERE lft >'.$arrP[0]['lft'];
	    			$stmt = $this->_db->query($sql);
	    			//
	    			$data['lft'] = $arrP[0]['lft']+1;
	    			$data['rgt'] = $arrP[0]['lft']+2;
    			}
    		}    		
    		$data['niv'] = $arrP[0]['niv']+1;
    	 	$data["acti_id"] = $this->insert($data);
    	 	$id = $data["acti_id"];
    	}
    	if($rData){
    		$data = $this->findByActi_id($id);
    		return $data[0];
    	}else
	    	return $id;
    } 
           
    /**
     * Recherche une entrée flux_acti avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
        $this->update($data, 'flux_acti.acti_id = ' . $id);
    }
    
    /**
     * Recherche une entrée flux_acti avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
        $acti = $this->findByActi_id($id);
        
        if(count($acti)==0)return;
    	
		$ids = $id;

		//récupère tous les enfants
    	$ids = $this->getFullChildIds($id);
		$ids = $ids[0]['ids'];
		if($ids) $ids.=','.$id;

		//suppression des données lieés
        $dt = $this->getDependentTables();
        foreach($dt as $t){
        	$dbT = new $t($this->_db);
        	$dbT->delete('acti_id IN ('.$ids.')');
        }        
        $this->delete('acti_id IN ('.$ids.')');
        
        //mis à jour des droites et gauches
        $sql = 'UPDATE flux_acti SET rgt = rgt-1, lft = lft - 1 WHERE lft BETWEEN '.$acti[0]['lft'].' AND '.$acti[0]['rgt'];
		$stmt = $this->_db->query($sql);
        $sql = 'UPDATE flux_acti SET rgt=rgt-2 WHERE rgt > '.$acti[0]['rgt'];
		$stmt = $this->_db->query($sql);
        $sql = 'UPDATE flux_acti SET lft=lft - 2 WHERE lft > '.$acti[0]['rgt'];
		$stmt = $this->_db->query($sql);
    }
    
    /**
     * Recherche une entrée flux_acti avec la valeur spécifiée
     * et retourne la liste de tous ses enfants au format csv
     *
     * @param integer $idActi
     * @param string $order
     * @return array
     */
    public function getFullChildIds($idActi, $order="lft")
    {
        $query = $this->select()
                ->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
            ->from(array('node' => 'flux_acti'),array("ids"=>"GROUP_CONCAT(enfants.acti_id)"))
            ->joinInner(array('enfants' => 'flux_acti'),
                'enfants.lft BETWEEN node.lft AND node.rgt',array('lib', 'acti_id'))
            ->where( "node.acti_id = ?", $idActi)
            ->group("node.acti_id");        
        $result = $this->fetchAll($query);
        return $result->toArray(); 
    }
    
    /**
     * Récupère toutes les entrées flux_acti avec certains critères
     * de tri, intervalles
     */
    public function getAll($order=null, $limit=0, $from=0)
    {
        $query = $this->select()
                    ->from( array("flux_acti" => "flux_acti") );
                    
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
    
     /**
     * Recherche la hierarchie d'une activité
     *
     * @param integer $idActi
     * @param string $order
     * @return array
     */
    public function getFullPath($idActi, $order="lft")
    {
        $query = $this->select()
                ->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
            ->from(array('node' => 'flux_acti'),array("parLib"=>"lib"))
            ->joinInner(array('parent' => 'flux_acti'),
                'node.lft BETWEEN parent.lft AND parent.rgt',array('lib', 'acti_id', 'niv'))
            ->where( "node.acti_id = ?", $idActi)
                        ->order("parent.".$order);        
                $result = $this->fetchAll($query);
        return $result->toArray(); 
    }
    

     /**
     * Recherche la hierarchie des activités pour un utilisateur
     *
     * @param integer $idOeu
     * @return array
     */
    public function getActiForOeuvre($idOeu)
    {
        $query = $this->select()
                ->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
            ->from(array('parent' => 'flux_acti'),array("parLib"=>"lib"))
            ->joinInner(array('node' => 'flux_acti'),
                'node.lft BETWEEN parent.lft AND parent.rgt',array('lib', 'acti_id', 'niv'))
            ->joinInner(array('au' => 'flux_actiuti'),
                'au.acti_id = node.acti_id',array('uti_id','validation','crea'))
            ->joinInner(array('oa' => 'gen_odu_acti'),
                'oa.acti_id = node.acti_id',array())
            ->joinInner(array('odu'=>'gen_oeuvres_dicos_utis')
            	, 'odu.id_odu = oa.id_odu AND odu.id_oeu = '.$idOeu,array())
            ->joinInner(array('u' => 'flux_uti')
            	, 'u.uti_id = odu.uti_id',array('login'))
			->order("node.acti_id")
			->group(array("node.acti_id", "odu.uti_id"));
        $result = $this->fetchAll($query)->toArray();
        $xml = "";
        $oActiId = 0;
        foreach ($result as $acti) {
        	if($xml==""){
        		$xml .= "<acti lib='".htmlspecialchars($acti['parLib'])."' idActi='1' >";
        		$xml .= '<acti lib="'.htmlspecialchars($acti['lib']).'" idActi="'.$acti['acti_id'].'" >';
        		$oActiId = $acti['acti_id'];        		
        	}
        	if($oActiId != $acti['acti_id']){
        		$xml .= "</acti>";
        		$xml .= '<acti lib="'.htmlspecialchars($acti['lib']).'" idActi="'.$acti['acti_id'].'" >';
        		$oActiId = $acti['acti_id'];        		
        	}
        	$action = " : action validée = ";
        	if($acti['validation']==1)$action.="OUI";
        	elseif($acti['validation']==2)$action.="En attente";
        	else $action.="NON";
        	$xml .= "<acti lib='".htmlspecialchars($acti['login']).$action."' idActi='".$acti['acti_id']."' idUti='".$acti['uti_id']."' validation='".$acti['validation']."' />";
        	
        }
        if($xml)$xml .= "</acti></acti>";
        
        return $xml; 
    }
    
    /**
     * Recherche une entrée flux_acti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $acti_id
     */
    public function findByActi_id($acti_id)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_acti") )                           
                    ->where( "f.acti_id = ?", $acti_id );

        return $this->fetchAll($query)->toArray(); 
    }
    /**
     * Recherche une entrée flux_acti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $idUti
     */
    public function findByIdUti($idUti)
    {
        $query = $this->select()
			->from( array("f" => "flux_acti") )                           
            ->where( "f.acti_id = ?", $acti_id );

        return $this->fetchAll($query)->toArray(); 
    }
    
    /**
     * Recherche une entrée flux_acti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param text $code
     */
    public function findByCode($code)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_acti") )                           
                    ->where( "f.code = ?", $code );

        return $this->fetchAll($query)->toArray(); 
    }
    /**
     * Recherche une entrée flux_acti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $desc
     */
    public function findByDesc($desc)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_acti") )                           
                    ->where( "f.desc = ?", $desc );

        return $this->fetchAll($query)->toArray(); 
    }

    /**
     * Recherche une entrée flux_acti avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $parent
     * 
     * @return Array
     */
    public function findByParent($parent)
    {
        $query = $this->select()
			->from( array("f" => "flux_acti") )                           
            ->where( "f.parent = ?", $parent);

        return $this->fetchAll($query)->toArray(); 
    }
    
    
    
}
