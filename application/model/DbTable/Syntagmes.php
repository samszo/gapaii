<?php
class Model_DbTable_Syntagmes extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_syntagmes';
    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	    
    
    public function obtenirSyntagme($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_syn = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    public function obtenirSyntagmeByDicoNum($idDico,$num)
    {
        $query = $this->select()
            ->where( "id_dico IN (?)",$idDico)
        	->where( "num = ?",$num)
            ;
		$r = $this->fetchRow($query);        
    	if (!$r) {
            return new Exception("Impossible de trouver le sntagme $num dans le dictionnaire $idDico");
        }
        return $r->toArray();
    }
        
	public function existeSyntagme($idDico, $num, $ordre, $lib)
    {
		$select = $this->select();
		$select->from($this, array('id_syn'))
			->where('id_dico = ?', $idDico)
			->where('num = ?', $num)
			->where('ordre = ?', $ordre)
			->where('lib = ?', $lib);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_syn; else $id=false;
        return $id;
    }    
    
    public function ajouterSyntagme($idDico, $num, $ordre, $lib="")
    {
    	$id = $this->existeSyntagme($idDico, $num, $ordre, $lib);
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
    
    public function modifierSyntagme($id, $num, $ordre, $lib)
    {
        $data = array(
            'num' => $num,
    		'ordre' => $ordre,
        	'lib' => $lib
        );
        $this->update($data, 'id_syn = '. (int)$id);
    }

    public function supprimerSyntagme($id)
    {
        $this->delete('id_syn =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
