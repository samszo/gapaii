<?php
class Model_DbTable_Determinants extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_determinants';
    protected $_referenceMap    = array(
        'Conjugaison' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	
    
    public function obtenirDeterminant($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_dtm = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    public function obtenirDeterminantByNum($num)
    {
        $rs = $this->fetchAll('num = '.$num);
        if (!$rs) {
            throw new Exception("Count not find row $id");
        }
        return $rs->toArray();
    }

    public function obtenirDeterminantByDicoNumNombre($idDico, $num, $pluriel)
    {
    	if($pluriel)    	
	        $where = 'id_dico IN ('.$idDico.') AND num = '.$num.' AND ordre > 3';
	    else
	    	$where = 'id_dico IN ('.$idDico.') AND num = '.$num.' AND ordre < 4';

		$query = $this->select()
        	->from( array("g" => "gen_determinants") )                           
            ->order("ordre")
        	->where($where);
	    $rs = $this->fetchAll($query);
	    
        if (!$rs) {
            throw new Exception("Count not find row $id");
        }
        return $rs->toArray();
    }
    
	public function existeDeterminant($idDico, $num, $ordre, $lib)
    {
		$select = $this->select();
		$select->from($this, array('id_dtm'))
			->where('id_dico = ?', $idDico)
			->where('lib = ?', $lib)
			->where('ordre = ?', $ordre)
			->where('num = ?', $num);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_dtm; else $id=false;
        return $id;
    }    
    
    public function ajouterDeterminant($idDico, $num, $ordre, $lib)
    {
    	$id = $this->existeDeterminant($idDico, $num, $ordre, $lib);
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
    
    public function modifierDeterminant($id, $num, $ordre, $lib)
    {
        $data = array(
            'num' => $num,
    		'ordre' => $ordre,
        	'lib' => $lib
        );
        $this->update($data, 'id_dtm = '. (int)$id);
    }

    public function supprimerDeterminant($id)
    {
        $this->delete('id_dtm =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
