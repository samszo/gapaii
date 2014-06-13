<?php
class Model_DbTable_ConceptsSyntagmes extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_concepts_syntagmes';
    
    protected $_referenceMap    = array(
        'Concept' => array(
            'columns'           => 'id_concept',
            'refTableClass'     => 'Model_DbTable_Concepts',
            'refColumns'        => 'id_concept'
        )
        ,'Syntagme' => array(
            'columns'           => 'id_syn',
            'refTableClass'     => 'Model_DbTable_Syntagmes',
            'refColumns'        => 'id_syn'
        )
	);	
    
    public function obtenirConceptSyntagme($idCon, $idSyn)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_syn = ' .$idSyn.' AND id_concept = ' .$idCon);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

	public function existeConceptSyntagme($idCon, $idSyn)
    {
		$select = $this->select();
		$select->from($this, array('id_concept'))
			->where('id_concept = ?', $idCon)
			->where('id_syn = ?', $idSyn);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_concept; else $id=false;
        return $id;
    }    
        
    public function ajouterConceptSyntagme($idCon, $idSyn)
    {
    	$id = $this->existeConceptSyntagme($idCon, $idSyn);
    	if(!$id){
	    	$data = array(
        	'id_concept' => $idCon
        	,'id_syn' => $idSyn
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierConceptSyntagme($idCon, $idSyn)
    {
        $data = array(
        	'id_concept' => $idCon
        	,'id_syn' => $idSyn
        );
        $this->update($data, 'id_syn = ' .$idSyn.' AND id_concept = ' .$idCon);
    }

    public function supprimerConceptSyntagme($idCon, $idSyn)
    {
        $this->delete('id_syn = ' .$idSyn.' AND id_concept = ' .$idCon);
    }

    public function supprimerConcept($idCon)
    {
        $this->delete('id_concept = ' .$idCon);
    }
    
}
