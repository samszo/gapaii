<?php
/**
 * Classe ORM qui représente la table 'Concepts'.
 *
 * @copyright  2010 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Concepts extends Zend_Db_Table_Abstract
{
    protected $_name = 'gen_concepts';
	protected $_dependentTables = array(
		'Model_DbTable_ConceptsVerbes'
		,'Model_DbTable_ConceptsAdjectifs'
		,'Model_DbTable_ConceptsSubstantifs'
		,'Model_DbTable_ConceptsSyntagmes'
		,'Model_DbTable_ConceptsGenerateurs'
		);
	
    protected $_referenceMap    = array(
        'Dico' => array(
            'columns'           => 'id_dico',
            'refTableClass'     => 'Model_DbTable_Dicos',
            'refColumns'        => 'id_dico'
        )
    );	
	
    /**
     * Récupère un concept par son identifiant.
     *
     * @param integer $id
     *
     * @return array
     */
    public function obtenirConcept($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id_concept = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    /**
     * Récupère les concepts par dictionnaire.
     *
     * @param integer $id
     *
     * @return array
     */
    public function obtenirConceptByDico($id)
    {
        $id = (int)$id;
        $query = $this->select()
            ->where( "id_dico = " . $id);
		$rs = $this->fetchAll($query);        
    	if (!$rs) {
            throw new Exception("Count not find rs $id");
        }
        return $rs->toArray();
    }

    /**
     * Récupère les concepts par type.
     *
     * @param integer $idDico
     * @param string $lib
     * @param string $type
     *
     * @return array
     */
    public function obtenirConceptByDicoLibType($idDico,$lib,$type)
    {
        $query = $this->select()
            ->where( "id_dico IN ($idDico)")
        	->where( "lib = ?",$lib)
            ->where( "type = ?",$type)
            ;
        $sql = $query->__toString();
		$r = $this->fetchRow($query);        
    	if (!$r) {
            $r = new Exception("La classe - $lib - de type - $type - n'a pas été trouvé dans le(s) dictionnaire(s) - $idDico -");
        }
        return $r;
    }
    
    /**
     * Récupère les concepts par type.
     *
     * @param integer $idDico
     * @param array $arrClass
     *
     * @return array
     */
    public function obtenirConceptDescription($idDico, $arrClass){

			$cpt = $this->obtenirConceptByDicoLibType($idDico,$arrClass[1],$arrClass[0]);
			//vérifie l'exeption
			if(get_class($cpt)=="Exception"){
				return $cpt;
			}
			$arrCpt = $cpt->toArray();
			
			//cherche les enfants suivant le type de concept
			$arrEnf = array();
			/*
			$tType ="";
			if($arrClass[0]=="a")$tType="Adjectifs";
			if($arrClass[0]=="v")$tType="Verbes";
			if($arrClass[0]=="m" || $arrClass[0]=="carac" || $arrClass[0]=="dis")$tType="Substantifs";
			if($arrClass[0]=="s")$tType="Syntagmes";
			if($arrClass[0]=="age" || $arrClass[0]=="univers")$tType="Generateurs";
			if($tType!=""){
				$enfants = $cpt->findManyToManyRowset('Model_DbTable_'.$tType,
	                                                 'Model_DbTable_Concepts'.$tType);
				$arrEnf = $enfants->toArray();
			}
			*/
			$enfants = $cpt->findManyToManyRowset('Model_DbTable_Adjectifs', 'Model_DbTable_ConceptsAdjectifs');
			$arr = $enfants->toArray();
			if(count($arr)>0)$arrEnf = array_merge($arrEnf,$arr);
			$enfants = $cpt->findManyToManyRowset('Model_DbTable_Verbes', 'Model_DbTable_ConceptsVerbes');
			$arr = $enfants->toArray();
			if(count($arr)>0)$arrEnf = array_merge($arrEnf,$arr);
			$enfants = $cpt->findManyToManyRowset('Model_DbTable_Substantifs', 'Model_DbTable_ConceptsSubstantifs');
			$arr = $enfants->toArray();
			if(count($arr)>0)$arrEnf = array_merge($arrEnf,$arr);
			$enfants = $cpt->findManyToManyRowset('Model_DbTable_Syntagmes', 'Model_DbTable_ConceptsSyntagmes');
			$arr = $enfants->toArray();
			if(count($arr)>0)$arrEnf = array_merge($arrEnf,$arr);
			
			//cherche les générateurs
			$gens = $cpt->findManyToManyRowset('Model_DbTable_Generateurs',
	                                                 'Model_DbTable_ConceptsGenerateurs');    	
			//cherche les générateurs
			//$cptgens = $this->findGensByIdconcept($arrCpt["id_concept"]);    	
			//return array("src"=>$arrCpt,"dst"=>array_merge($arrEnf,$gens->toArray(),$cptgens));
			
			return array("src"=>$arrCpt,"dst"=>array_merge($arrEnf,$gens->toArray()));
    }

	/*
     * Recherche les générateurs liés aux concept
     *
     * @param int $idConcept
     * 
     * @return array
     */
    public function findGensByIdconcept($idConcept)
    {
        $query = $this->select()
        	->setIntegrityCheck(false) //pour pouvoir sélectionner des colonnes dans une autre table
            ->from(array('cg' => 'gen_concepts_generateurs'))
            ->joinInner(array('g' => 'gen_generateurs'),
            	'g.id_gen = cg.id_gen',array('id_dico','valeur'))
            ->where( "cg.id_concept = ?", $idConcept);
    
        return $this->fetchAll($query)->toArray(); 
    }
        
    
    /**
     * Vérifie si un concept existe.
     *
     * @param integer $idDico
     * @param string $lib
     * @param string $type
     *
     * @return integer
     */
    public function existeConcept($idDico, $lib, $type)
    {
		$select = $this->select();
		$select->from($this, array('id_concept'))
			->where('id_dico = ?', $idDico)
			->where('lib = ?', $lib)
			->where('type = ?', $type);
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_concept; else $id=false;
        return $id;
    }    
    
    /**
     * Ajoute un concept
     *
     * @param integer $idDico
     * @param string $lib
     * @param string $type
     * @param boolean $existe
     *
     * @return integer
     */
    public function ajouterConcept($idDico, $lib, $type, $existe=true)
    {
    	$id = false;
    	if($existe)
    		$id = $this->existeConcept($idDico, $lib, $type);
    	if(!$id){
    		$data = array(
            'id_dico' => $idDico,
            'lib' => $lib,
            'type' => $type
	        );
    	 	$id = $this->insert($data);
    	}
    	return $id;
    }
    
    /**
     * Modifier un concept
     *
     * @param integer $idDico
     * @param string $lib
     * @param string $type
     *
     */
    public function modifierConcept($id, $lib, $type)
    {
        $data = array(
            'lib' => $lib,
            'type' => $type
        );
        $this->update($data, 'id_concept = '. (int)$id);
    }

    /**
     * Supprime un concept
     *
     * @param integer $id
     *
     */
    public function supprimerConcept($id)
    {
    	foreach($this->_dependentTables as $t){
			$tEnfs = new $t();
			$tEnfs->supprimerConcept($id);
		}
    	$this->delete('id_concept =' . (int)$id);
    }

    /**
     * Supprime les concept d'un dicotionnaire
     *
     * @param integer $id
     * 
     */
    public function supprimerDico($id)
    {
    	$arr = $this->obtenirConceptByDico($id);
		foreach($arr as $enf){
    		$this->supprimerConcept($enf["id_concept"]);	
    	}    	
    	$this->delete('id_dico =' . (int)$id);
    }
    
}
