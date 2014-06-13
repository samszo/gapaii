<?php

class Form_Verbe extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('verbe');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);
        
        $CacheForm = new Zend_Form_Element_Hidden('CacheForm');
        $CacheForm->setValue(false);
        
		$dbDico = new Model_DbTable_Dicos();
		$arrDicos = $dbDico->obtenirDicoType("conjugaisons");
    	$multiOptions = array();
		foreach ($arrDicos as $dico) {
		    $multiOptions[$dico["id_dico"]] = $dico["nom"];
		    $idDicoConj = $dico["id_dico"];
		}
		$mcbDico = new Zend_Form_Element_Select('dicoIdCjg', array(
		    'multiOptions' => $multiOptions
		));
		$mcbDico->setLabel('Choisir le dictionnaires de conjugaison');		
		$mcbDico->setValue($idDicoConj);
    	$mcbDico->setRequired(true);
        
		//construction des modèle de conjugaison
    	$dbConj = new Model_DbTable_Conjugaisons();
    	$RsConjs = $dbConj->obtenirConjugaisonListeModeles($idDicoConj);   	
    	$conj = new Zend_Form_Element_Select('id_conj', array(
		    'multiOptions' => $RsConjs));
		$conj->setRequired(true);
        $conj->setLabel('Choisir un modèle de conjugaison:');
    	
      	$eli = new Zend_Form_Element_Select('elision', array(
		    'multiOptions' => array(1=>"avec",0=>"sans")));
      	$eli->setRequired(true);
		$eli->setLabel("Définir l'élision:");

      	$prefix = new Zend_Form_Element_Text('prefix');
      	$prefix->setRequired(false);
		$prefix->setLabel('Définir un prefix:');
		
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
        
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $CacheForm, $mcbDico, $conj, $eli, $prefix, $envoyer));
    }
}
