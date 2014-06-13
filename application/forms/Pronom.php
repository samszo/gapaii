<?php
class Form_Pronom extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('pronom');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);
        
        $type = new Zend_Form_Element_Hidden('type');
        $type->setValue($options["type"]);
        
        $lib = new Zend_Form_Element_Text('lib');
      	$lib->setRequired(true);
		$lib->setLabel('Définir un libellé');
      	
      	$lib_eli = new Zend_Form_Element_Text('lib_eli');
      	$lib_eli->setRequired(true);
		$lib_eli->setLabel('Définir une élision');
		
		$num = new Zend_Form_Element_Text('num');
      	$num->setRequired(true);
		$num->setLabel('Définir un numéro');
		
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
        
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $type, $lib, $lib_eli, $num, $envoyer));
    }
}
