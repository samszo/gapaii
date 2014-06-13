<?php
class Model_DbTable_DicosDicos extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_dicos_dicos';
    
    protected $_referenceMap    = array(
        'DicoGen' => array(
            'columns'           => 'id_dico_gen',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
        ,'DicoRef' => array(
            'columns'           => 'id_dico_ref',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
	);	
    
    public function obtenirDicoGenDicosRefs($idDicoGen)
    {
        $id = (int)$idDicoGen;
        $row = $this->fetchRow('id_dico_gen = ' .$id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    public function obtenirDicoGenByDicosRefs($idDicoRef)
    {
        $id = (int)$idDicoRef;
        $row = $this->fetchRow('id_dico_ref = ' .$id);
        if (!$row) {
            return false;
        }
        return $row->toArray();
    }
    
    public function existeDicoGenDicoRef($idDicoGen, $idDicoRef)
    {
		$select = $this->select();
		$select->from($this, array('id_dico_gen'))
			->where('id_dico_gen = ?', $idDicoGen)
			->where('id_dico_ref = ?', $idDicoRef);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_dico_gen; else $id=false;
        return $id;
    }    
    
    public function ajouterDicoGenDicoRef($idDicoGen, $idDicoRef)
    {
    	$id = $this->existeDicoGenDicoRef($idDicoGen, $idDicoRef);
    	if(!$id){
	    	$data = array(
	        'id_dico_gen' => $idDicoGen
	        ,'id_dico_ref' => $idDicoRef
	        );
			$this->insert($data);
    	}
    }
    
    public function supprimerDicoGenDicoRef($idDicoGen, $idDicoRef)
    {
        $this->delete('id_dico_gen = ' .$idDicoGen.' AND id_dico_ref = ' .$idDicoRef);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico_ref =' . (int)$id);
    }
    
}
