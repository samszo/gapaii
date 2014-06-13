<?php
class Form_Substantif extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('substantif');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);
        
      	$eli = new Zend_Form_Element_Select('elision', array(
		    'multiOptions' => array(1=>"avec",0=>"sans")));
      	$eli->setRequired(true);
		$eli->setLabel("Définir l'élision:");
        
		$prefix = new Zend_Form_Element_Text('prefix');
      	$prefix->setRequired(true);
		$prefix->setLabel('Définir un prefixe');

		$s = new Zend_Form_Element_Text('s');
		$s->setLabel('Définir le singulier');

		$p = new Zend_Form_Element_Text('p');
      	$p->setRequired(false);
		$p->setLabel('Définir le pluriel');
		
      	$g = new Zend_Form_Element_Select('genre', array(
		    'multiOptions' => array(2=>"féminin",1=>"masculin")));
		$g->setRequired(true);
		$g->setLabel('Définir le genre');
		
		$envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $eli, $prefix, $s, $p, $g, $envoyer));
    }
}
