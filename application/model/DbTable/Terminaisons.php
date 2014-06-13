<?php
class Model_DbTable_Terminaisons extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_terminaisons';
    protected $_referenceMap    = array(
        'Verbe' => array(
            'columns'           => 'id_conj',
            'refTableClass'     => 'Model_DbTable_Conjugaisons',
            'refColumns'        => 'id_conj'
        )
    );	
    
    public function obtenirTerminaison($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_trm = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
    public function obtenirConjugaison($id)
    {
        $id = (int)$id;
		$query = $this->select()
            ->where('id_conj = ' . $id)
            ->order('num') ;
        
        $rows = $this->fetchAll($query);
        if (!$rows) {
            throw new Exception("Count not find row $id");
        }
        return $rows->toArray();
    }
   
    public function obtenirConjugaisonByConjNum($idConj,$num)
    {
        $query = $this->select()
            ->where( "id_conj = ?",$idConj)
        	->where( "num = ?",$num)
        	;
		$r = $this->fetchRow($query);        
    	if (!$r) {
            return new Exception("La terminaison - $num - de la conjugaison - $idConj - n'a pas été trouvé");
        }
        return $r->toArray();
    }
    
    public function existeTerminaison($idConj, $num, $lib)
    {
		$select = $this->select();
		$select->from($this, array('id_trm'))
			->where('id_conj = ?', $idConj)
			->where('num = ?', $num)
			->where('lib = ?', $lib);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_trm; else $id=false;
        return $id;
    }    
    
    public function ajouterTerminaison($idConj, $num, $lib)
    {
    	$id = $this->existeTerminaison($idConj, $num, $lib);
    	if(!$id){
    		$data = array(
	            'id_conj' => $idConj,
	            'num' => $num,
	            'lib' => $lib);
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierTerminaison($id, $num, $lib)
    {
        $data = array(
            'num' => $num,
            'lib' => $lib
        );
        $this->update($data, 'id_trm = '. (int)$id);
    }

    public function supprimerTerminaison($id)
    {
        $this->delete('id_trm =' . (int)$id);
    }
    
    public function supprimerConjugaison($id)
    {
        $this->delete('id_conj =' . (int)$id);
    }
    
}
