<?php
class Model_DbTable_ConceptsGenerateurs extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_concepts_generateurs';
    
    protected $_referenceMap    = array(
        'Concept' => array(
            'columns'           => 'id_concept',
            'refTableClass'     => 'Model_DbTable_Concepts',
            'refColumns'        => 'id_concept'
        )
        ,'Generateur' => array(
            'columns'           => 'id_gen',
            'refTableClass'     => 'Model_DbTable_Generateurs',
            'refColumns'        => 'id_gen'
        )
	);	
    
    public function obtenirConceptGenerateur($idCon, $idGen)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_gen = ' .$idGen.' AND id_concept = ' .$idCon);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

	public function existeConceptGenerateur($idCon, $idGen)
    {
		$select = $this->select();
		$select->from($this, array('id_concept'))
			->where('id_concept = ?', $idCon)
			->where('id_gen = ?', $idGen);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_concept; else $id=false;
        return $id;
    }    
    
    public function ajouterConceptGenerateur($idCon, $idGen)
    {
    	$id = $this->existeConceptGenerateur($idCon, $idGen);
    	if(!$id){
	    	$data = array(
        	'id_concept' => $idCon
        	,'id_gen' => $idGen
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierConceptGenerateur($idCon, $idGen)
    {
        $data = array(
        	'id_concept' => $idCon
        	,'id_gen' => $idGen
        );
        $this->update($data, 'id_gen = ' .$idGen.' AND id_concept = ' .$idCon);
    }

    public function supprimerConceptGenerateur($idCon, $idGen)
    {
        $this->delete('id_gen = ' .$idGen.' AND id_concept = ' .$idCon);
    }

    public function supprimerConcept($idCon)
    {
        $this->delete('id_concept = ' .$idCon);
    }
    
}
