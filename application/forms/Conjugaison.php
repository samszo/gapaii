<?php

class Form_Conjugaison extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('conjugaison');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);
        
      	$modele = new Zend_Form_Element_Text('modele');
      	$modele->setRequired(true);
		$modele->setLabel('Définir un modèle');
      	
      	$num = new Zend_Form_Element_Text('num');
      	$num->setRequired(true);
		$num->setLabel('Définir un numéro');
      	
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $modele, $num, $envoyer));
    }
}
