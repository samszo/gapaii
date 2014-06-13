<?php
class Model_DbTable_ConceptsAdjectifs extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_concepts_adjectifs';
    
    protected $_referenceMap    = array(
        'Concept' => array(
            'columns'           => 'id_concept',
            'refTableClass'     => 'Model_DbTable_Concepts',
            'refColumns'        => 'id_concept'
        )
        ,'Adjectif' => array(
            'columns'           => 'id_adj',
            'refTableClass'     => 'Model_DbTable_Adjectifs',
            'refColumns'        => 'id_adj'
        )
	);	
    
    public function obtenirConceptAdjectif($idCon, $idAdj)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_adj = ' .$idAdj.' AND id_concept = ' .$idCon);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

	public function existeConceptAdjectif($idCon, $idAdj)
    {
		$select = $this->select();
		$select->from($this, array('id_concept'))
			->where('id_concept = ?', $idCon)
			->where('id_adj = ?', $idAdj);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_concept; else $id=false;
        return $id;
    }    
    
    public function ajouterConceptAdjectif($idCon, $idAdj)
    {
    	$id = $this->existeConceptAdjectif($idCon, $idAdj);
    	if(!$id){
	    	$data = array(
        	'id_concept' => $idCon
        	,'id_adj' => $idAdj
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierConceptAdjectif($idCon, $idAdj)
    {
        $data = array(
        	'id_concept' => $idCon
        	,'id_adj' => $idAdj
        );
        $this->update($data, 'id_adj = ' .$idAdj.' AND id_concept = ' .$idCon);
    }

    public function supprimerConceptAdjectif($idCon, $idAdj)
    {
        $this->delete('id_adj = ' .$idAdj.' AND id_concept = ' .$idCon);
    }

    public function supprimerConcept($idCon)
    {
        $this->delete('id_concept = ' .$idCon);
    }
    
}
