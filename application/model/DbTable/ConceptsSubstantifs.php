<?php
class Model_DbTable_ConceptsSubstantifs extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_concepts_substantifs';
    
    protected $_referenceMap    = array(
        'Concept' => array(
            'columns'           => 'id_concept',
            'refTableClass'     => 'Model_DbTable_Concepts',
            'refColumns'        => 'id_concept'
        )
        ,'Substantif' => array(
            'columns'           => 'id_sub',
            'refTableClass'     => 'Model_DbTable_Substantifs',
            'refColumns'        => 'id_sub'
        )
	);	
    
    public function obtenirConceptSubstantif($idCon, $idSub)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_sub = ' .$idSub.' AND id_concept = ' .$idCon);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

	public function existeConceptSubstantif($idCon, $idSub)
    {
		$select = $this->select();
		$select->from($this, array('id_concept'))
			->where('id_concept = ?', $idCon)
			->where('id_sub = ?', $idSub);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_concept; else $id=false;
        return $id;
    }    
    
    public function ajouterConceptSubstantif($idCon, $idSub)
    {
    	$id = $this->existeConceptSubstantif($idCon, $idSub);
    	if(!$id){
	    	$data = array(
        	'id_concept' => $idCon
        	,'id_sub' => $idSub
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierConceptSubstantif($idCon, $idSub)
    {
        $data = array(
        	'id_concept' => $idCon
        	,'id_sub' => $idSub
        );
        $this->update($data, 'id_sub = ' .$idSub.' AND id_concept = ' .$idCon);
    }

    public function supprimerConceptSubstantif($idCon, $idSub)
    {
        $this->delete('id_sub = ' .$idSub.' AND id_concept = ' .$idCon);
    }
    
    public function supprimerConcept($idCon)
    {
        $this->delete('id_concept = ' .$idCon);
    }
    
}
