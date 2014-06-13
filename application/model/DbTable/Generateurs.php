<?php
class Model_DbTable_Generateurs extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_generateurs';

    protected $_dependentTables = array('Model_DbTable_ConceptsGenerateurs');
    
    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	
    
    public function obtenirGenerateur($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_gen = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
	public function existeGenerateur($idDico, $valeur)
    {
		$select = $this->select();
		$select->from($this, array('id_gen'))
			->where('id_dico = ?', $idDico)
			->where('valeur = ?', $valeur);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_gen; else $id=false;
        return $id;
    }    
    
    public function ajouterGenerateur($idDico, $valeur)
    {
    	$id = $this->existeGenerateur($idDico, $valeur);
    	if(!$id){
	    	$data = array(
            'id_dico' => $idDico,
            'valeur' => $valeur
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierGenerateur($id, $valeur)
    {
        $data = array(
        	'valeur' => $valeur
        );
        $this->update($data, 'id_gen = '. (int)$id);
    }

    public function supprimerGenerateur($id)
    {
        $this->delete('id_gen =' . (int)$id);
    }
    
    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
