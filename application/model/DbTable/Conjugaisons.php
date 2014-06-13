<?php
class Model_DbTable_Conjugaisons extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_conjugaisons';
	protected $_dependentTables = array('Model_DbTable_Terminaisons','Model_DbTable_Verbes');

    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	
	
    public function obtenirConjugaison($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_conj = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }


    public function obtenirConjugaisonIdByNumModele($idDico, $num)
    {
		$select = $this->select();
		$select->from($this, array('id_conj'))
			->where('id_dico = ?', $idDico)
			->where('num = ?', $num);
		$rs = $this->fetchAll($select);        
    	if (!$rs) {
            throw new Exception("Count not find rs $id");
        }
        if($rs->count()==0)
        	return -1;
        else
	        return $rs[0]->id_conj;
    }

    public function obtenirConjugaisonDico($idDico)
    {
		$select = $this->select();
		$select->from($this, array('id_conj', 'id_dico', 'num', 'modele'))
			->where('id_dico = ?', $idDico)
			->order('modele');
		$rs = $this->fetchAll($select);        
    	if (!$rs) {
            throw new Exception("Count not find rs $id");
        }
        return $rs->toArray();
    }

    public function obtenirConjugaisonListeModeles($idDico)
    {
		$dbDics = new Model_DbTable_DicosDicos();
		$idDicos = $dbDics->obtenirDicoGenDicosRefs($idDico);
    	$rs = $this->obtenirConjugaisonDico($idDicos['id_dico_ref']);
    	if (!$rs) {
            throw new Exception("Count not find rs $idDico - ".$idDicos['id_dico_ref']);
        }
    	foreach($rs as $r){
        	$arr[$r['id_conj']]=$r['modele'];	
        }
        return $arr;
    }
    
	public function existeConjugaison($idDico, $num, $modele)
    {
		$select = $this->select();
		$select->from($this, array('id_conj'))
			->where('id_dico = ?', $idDico)
			->where('modele = ?', $modele)
			->where('num = ?', $num);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_conj; else $id=false;
        return $id;
    }    
    
    public function ajouterConjugaison($idDico, $num, $modele)
    {
    	$id = $this->existeConjugaison($idDico, $num, $modele);
    	if(!$id){
    		$data = array(
            'id_dico' => $idDico,
            'num' => $num,
            'modele' => $modele
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    public function modifierConjugaison($id, $num, $modele)
    {
        $data = array(
            'num' => $num,
            'modele' => $modele
        );
        $this->update($data, 'id_conj = '. (int)$id);
    }

    public function supprimerConjugaison($id)
    {
    	$tEnfs = new Model_DbTable_Terminaisons;
   		$tEnfs->supprimerConjugaison($id);	
    	$this->delete('id_conj =' . (int)$id);
    }

    public function supprimerDico($id)
    {
    	$arr = $this->obtenirConjugaisonDico($id);
		foreach($arr as $enf){
    		$this->supprimerConjugaison($enf["id_conj"]);	
    	}    	
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
