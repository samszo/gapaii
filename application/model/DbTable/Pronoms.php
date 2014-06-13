<?php
class Model_DbTable_Pronoms extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_pronoms';
    protected $_referenceMap    = array(
        'Verbe' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	    
    
    public function obtenirPronom($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_pronom = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

   public function obtenirPronomByDicoNumType($idDico,$num,$type)
    {
        $query = $this->select()
            ->where( "id_dico IN (".$idDico.")")
        	->where( "num = ?",$num)
        	->where( "type = ?",$type)
        	;
		$r = $this->fetchRow($query);        
    	if (!$r) {
            return new Exception("Le pronom - $num - de type - $type - n'a pas été trouvé");
        }
        
        return $r->toArray();
    }
    
	public function existePronom($idDico, $num, $lib, $lib_eli, $type)
    {
		$select = $this->select();
		$select->from($this, array('id_pronom'))
			->where('id_dico = ?', $idDico)
			->where('num = ?', $num)
			->where('lib = ?', $lib)
			->where('lib_eli = ?', $lib_eli)
			->where('type = ?', $type);
		$rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_pronom; else $id=false;
        return $id;
    }    
    
    public function ajouterPronom($idDico, $num, $lib, $lib_eli, $type)
    {
    	$id = $this->existePronom($idDico, $num, $lib, $lib_eli, $type);
    	if(!$id){
	    	$data = array(
	            'id_dico' => $idDico,
	            'num' => $num,
	    		'lib' => $lib,
	            'lib_eli' => $lib_eli,
	            'type' => $type
	    	);
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierPronom($id, $num, $lib, $lib_eli, $type)
    {
        $data = array(
            'num' => $num,
    		'lib' => $lib,
        	'lib_eli' => $lib_eli,
            'type' => $type
        );
        $this->update($data, 'id_pronom = '. (int)$id);
    }

    public function supprimerPronom($id)
    {
        $this->delete('id_pronom =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$this->delete('id_dico =' . (int)$id);
    }
    
}