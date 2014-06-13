<?php
class Model_DbTable_Verbes extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_verbes';

    protected $_dependentTables = array('Model_DbTable_ConceptsVerbes');
    
    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
        ,'Conjugaison' => array(
            'columns'           => 'id_conj',
            'refTableClass'     => 'Model_DbTable_Conjugaisons',
            'refColumns'        => 'id_conj'
        )
        );	
	
    public function obtenirVerbe($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_verbe = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
    public function ajouterVerbe($idDico, $idConj, $eli, $prefix)
    {
    	$id = $this->existeVerbe($idDico, $idConj, $eli, $prefix);
    	if(!$id){
    	   	$data = array(
            'id_dico' => $idDico,
            'id_conj' => $idConj,
    		'elision' => $eli,
    		'prefix' => $prefix
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }

	public function existeVerbe($idDico, $idConj, $eli, $prefix)
    {
		$select = $this->select();
		$select->from($this, array('id_verbe'))
			->where('id_dico = ?', $idDico)
			->where('id_conj = ?', $idConj)
			->where('elision = ?', $eli)
			->where('prefix = ?', $prefix);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_verbe; else $id=false;
        return $id;
    }    
    
    public function modifierVerbe($id, $idConj, $eli, $prefix, $modele)
    {
        $data = array(
            'id_conj' => $idConj,
    		'elision' => $eli,
    		'prefix' => $prefix
        );
        $this->update($data, 'id_verbe = '. (int)$id);
    }

    public function supprimerVerbe($id)
    {
    	$this->delete('id_verbe =' . (int)$id);
    }
    
    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
