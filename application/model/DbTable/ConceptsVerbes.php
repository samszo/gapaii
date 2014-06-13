<?php
class Model_DbTable_ConceptsVerbes extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_concepts_verbes';
    
    protected $_referenceMap    = array(
        'Concept' => array(
            'columns'           => 'id_concept',
            'refTableClass'     => 'Model_DbTable_Concepts',
            'refColumns'        => 'id_concept'
        )
        ,'Verbe' => array(
            'columns'           => 'id_verbe',
            'refTableClass'     => 'Model_DbTable_Verbes',
            'refColumns'        => 'id_verbe'
        )
	);	
    
    public function obtenirConceptVerbe($idCon, $idVerbe)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_verbe = ' .$idVerbe.' AND id_concept = ' .$idCon);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
	public function existeConceptVerbe($idCon, $idVerbe)
    {
		$select = $this->select();
		$select->from($this, array('id_concept'))
			->where('id_concept = ?', $idCon)
			->where('id_verbe = ?', $idVerbe);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_concept; else $id=false;
        return $id;
    }    
    
    public function ajouterConceptVerbe($idCon, $idVerbe)
    {    	
    	$id = $this->existeConceptVerbe($idCon, $idVerbe);
    	if(!$id){
	    	$data = array(
        	'id_concept' => $idCon
        	,'id_verbe' => $idVerbe
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierConceptVerbe($idCon, $idVerbe)
    {
        $data = array(
        	'id_concept' => $idCon
        	,'id_verbe' => $idVerbe
        );
        $this->update($data, 'id_verbe = ' .$idVerbe.' AND id_concept = ' .$idCon);
    }

    public function supprimerConceptVerbe($idCon, $idVerbe)
    {
        $this->delete('id_verbe = ' .$idVerbe.' AND id_concept = ' .$idCon);
    }
    
    public function supprimerConcept($idCon)
    {
        $this->delete('id_concept = ' .$idCon);
    }
    
}
