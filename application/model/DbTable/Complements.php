<?php
class Model_DbTable_Complements extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_complements';
    protected $_referenceMap    = array(
        'Verbe' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	
    
    public function obtenirComplement($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_cpm = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }


	public function existeComplement($idDico, $num, $ordre, $lib)
    {
		$select = $this->select();
		$select->from($this, array('id_cpm'))
			->where('id_dico = ?', $idDico)
			->where('lib = ?', $lib)
			->where('num = ?', $num)
			->where('ordre = ?', $ordre);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_cpm; else $id=false;
        return $id;
    }    
    
    public function ajouterComplement($idDico, $num, $ordre, $lib)
    {
    	$id = $this->existeComplement($idDico, $num, $ordre, $lib);
    	if(!$id){
    		$data = array(
            'id_dico' => $idDico,
            'num' => $num,
    		'ordre' => $ordre,
            'lib' => $lib
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierComplement($id, $num, $ordre, $lib)
    {
        $data = array(
            'num' => $num,
    		'ordre' => $ordre,
        	'lib' => $lib
        );
        $this->update($data, 'id_cpm = '. (int)$id);
    }

    public function supprimerComplement($id)
    {
        $this->delete('id_cpm =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
