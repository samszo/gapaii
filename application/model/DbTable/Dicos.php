<?php
class Model_DbTable_Dicos extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_dicos';
	protected $_dependentTables = array(
		'Model_DbTable_Conjugaisons'
		,'Model_DbTable_Determinants'
		,'Model_DbTable_Complements'
		,'Model_DbTable_Syntagmes'
		,'Model_DbTable_Concepts'
		,'Model_DbTable_DicosDicos'
		,'Model_DbTable_Substantifs'
		,'Model_DbTable_Adjectifs'
		,'Model_DbTable_Pronoms'
		,'Model_DbTable_Negations'
		,'Model_DbTable_Verbes'
		,'Model_DbTable_Generateurs'
		);

	public function getItemsDico($id)
    {
        $id = (int)$id;
		$Rowset = $this->find($id);
		$dico = $Rowset->current();
		if($dico['type']=='conjugaisons')
			$items = $dico->findDependentRowset('Model_DbTable_Conjugaisons');
		if($dico['type']=='déterminants')
			$items = $dico->findDependentRowset('Model_DbTable_Determinants');
		if($dico['type']=='compléments')
			$items = $dico->findDependentRowset('Model_DbTable_Complements');
		if($dico['type']=='syntagmes')
			$items = $dico->findDependentRowset('Model_DbTable_Syntagmes');
		if($dico['type']=='concepts')
			$items = $dico->findDependentRowset('Model_DbTable_Concepts');
		if($dico['type']=='pronoms_complement')
			$items = $dico->findDependentRowset('Model_DbTable_Pronoms');
		if($dico['type']=='pronoms_sujet')
			$items = $dico->findDependentRowset('Model_DbTable_Pronoms');
		if($dico['type']=='négations')
			$items = $dico->findDependentRowset('Model_DbTable_Negations');
			
        return $items;
    }
	
    /**
     * Vérifie si une entrée existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('id_dico'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_dico; else $id=false;
        return $id;
    } 
        
	public function obtenirDicoType($type)
    {
		$select = $this->select();
		$select->where('type = ?', $type);
	    $rs = $this->fetchAll($select);        
    	if (!$rs) {
            throw new Exception("Count not find rs $id");
        }
        return $rs->toArray();
	}
    
    public function obtenirDico($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_dico = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    public function ajouterDico($url, $nom, $type, $urlS="", $pathS="", $langue="")
    {
        //mettre utf8_decode pour php 5.3
    	$data = array(
            'nom' => $nom,
    		'url' => $url,
            'url_source' => $urlS,
            'path_source' => $pathS,
    		'type' => $type,
    		'langue' => $langue
        );
    	$idDico = $this->existe($data); 
    	if(!$idDico)
        	$idDico = $this->insert($data);
    	return $idDico;
    }
    
    public function modifierDico($id, $nom, $langue, $url, $type, $urlS)
    {
        $data = array(
            'nom' => $nom,
            'langue' => $langue,
        	'url' => $url,
            'url_source' => $urlS,
        	'type' => $type,
            'maj' => new Zend_Db_Expr('NOW()')
        );
        print_r($data);
        $this->update($data, 'id_dico = '. (int)$id);
    }

    public function supprimerDico($id)
    {
    	//vérifier s'il n'y a pas de dictionnaire lié
    	$dbDicoDico = new Model_DbTable_DicosDicos();
    	$arr = $dbDicoDico->obtenirDicoGenByDicosRefs($id);
    	
    	if($arr){
	   		throw new Exception("Impossible de supprimer ce dictionnaire.\nC'est une référence pour d'autres dictionnaires.");
    	}
    	
    	foreach($this->_dependentTables as $t){
			$tEnfs = new $t();
			$tEnfs->supprimerDico($id);
		}
    	$this->delete('id_dico =' . (int)$id);
    }
    
}