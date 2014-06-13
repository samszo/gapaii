<?php
class Model_DbTable_Negations extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_negations';
    protected $_referenceMap    = array(
        'Verbe' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	    
    
    public function obtenirNegation($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_negation = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

   public function obtenirNegationByDicoNum($idDico,$num)
    {
        $query = $this->select()
            ->where( "id_dico IN (?)",$idDico)
        	->where( "num = ?",$num)
            ;
		$r = $this->fetchRow($query);        
    	if (!$r) {
            throw new Exception("Count not find rs $idDico,$num");
        }
        return $r->toArray();
    }
    
	public function existeNegation($idDico, $num, $lib)
    {
		$select = $this->select();
		$select->from($this, array('id_negation'))
			->where('id_dico = ?', $idDico)
			->where('num = ?', $num)
			->where('lib = ?', $lib);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_pronom; else $id=false;
        return $id;
    }    
    
    public function ajouterNegation($idDico, $num, $lib)
    {
    	$id = $this->existeNegation($idDico, $num, $lib);
    	if(!$id){
	    	$data = array(
	            'id_dico' => $idDico,
	            'num' => $num,
	    		'lib' => $lib
	    	);
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierNegation($id, $num, $lib)
    {
        $data = array(
            'num' => $num,
    		'lib' => $lib
        );
        $this->update($data, 'id_negation = '. (int)$id);
    }

    public function supprimerNegation($id)
    {
        $this->delete('id_negation =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}