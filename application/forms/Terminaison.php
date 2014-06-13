<?php
class Form_Terminaison extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('terminaison');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);
        
      	$lib = new Zend_Form_Element_Text('lib');
      	$lib->setRequired(true);
		$lib->setLabel('Définir un libellé');
      	
      	$num = new Zend_Form_Element_Text('num');
      	$num->setRequired(true);
		$num->setLabel('Définir un numéro');
      	
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $lib, $num, $envoyer));
    }
}
