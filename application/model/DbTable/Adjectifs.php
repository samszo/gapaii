<?php
class Model_DbTable_Adjectifs extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_adjectifs';
	
    protected $_dependentTables = array('Model_DbTable_ConceptsAdjectifs');
    
    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
        );	
	
    public function obtenirAdjectif($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_adj = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
	public function existeAdjectif($idDico, $eli, $prefix, $ms, $fs, $mp, $fp)
    {
		$select = $this->select();
		$select->from($this, array('id_adj'))
			->where('id_dico = ?', $idDico)
			->where('elision = ?', $eli)
			->where('prefix = ?', $prefix)
			->where('m_s = ?', $ms)
			->where('f_s = ?', $fs)
			->where('m_p = ?', $mp)
			->where('f_p = ?', $fp);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_adj; else $id=false;
        return $id;
    }    
    
    public function ajouterAdjectif($idDico, $eli, $prefix, $ms, $fs, $mp, $fp)
    {
    	$id = $this->existeAdjectif($idDico, $eli, $prefix, $ms, $fs, $mp, $fp);
    	if(!$id){
    		$data = array(
	            'id_dico' => $idDico,
	    		'elision' => $eli,
	    		'prefix' => $prefix,
	            'm_s' => $ms,
	            'f_s' => $fs,
	            'm_p' => $mp,
	            'f_p' => $fp
	    	);
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierAdjectif($id,  $eli, $prefix, $ms, $fs, $mp, $fp)
    {
        $data = array(
    		'elision' => $eli,
    		'prefix' => $prefix,
            'm_s' => $ms,
            'f_s' => $fs,
            'm_p' => $mp,
            'f_p' => $fp
        );
        $this->update($data, 'id_adj = '. (int)$id);
    }

    public function supprimerAdjectif($id)
    {
    	$this->delete('id_adj =' . (int)$id);
    }
    
    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
    
}
