<?php
class Model_DbTable_Substantifs extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_substantifs';

    protected $_dependentTables = array('Model_DbTable_ConceptsSubstantifs');
    
    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
        );	
	
    public function obtenirSubstantif($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_sub = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
	public function existeSubstantif($idDico, $eli, $prefix, $s, $p, $genre)
    {
		$select = $this->select();
		$select->from($this, array('id_sub'))
			->where('id_dico = ?', $idDico)
			->where('elision = ?', $eli)
			->where('prefix = ?', $prefix)
			->where('s = ?', $s)
			->where('p = ?', $p)
			->where('genre = ?', $genre)
			;
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_sub; else $id=false;
        return $id;
    }    
    
    public function ajouterSubstantif($idDico, $eli, $prefix, $s, $p, $genre)
    {
    	$id = $this->existeSubstantif($idDico, $eli, $prefix, $s, $p, $genre);
    	if(!$id){
	    	$data = array(
	            'id_dico' => $idDico,
	    		'elision' => $eli,
	    		'prefix' => $prefix,
	            's' => $s,
	            'p' => $p,
	            'genre' => $genre
	    	);
	    	$id = $this->insert($data);
    	}
        return $id;
    }
    
    public function modifierSubstantif($id,  $eli, $prefix, $s, $p, $genre)
    {
        $data = array(
    		'elision' => $eli,
    		'prefix' => $prefix,
            's' => $s,
            'p' => $p,
            'genre' => $genre
        );
        $this->update($data, 'id_sub = '. (int)$id);
    }

    public function supprimerSubstantif($id)
    {
    	$this->delete('id_sub =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
