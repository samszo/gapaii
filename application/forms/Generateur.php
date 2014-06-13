<?php
class Form_Generateur extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('generateur');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);

        $idDico = new Zend_Form_Element_Hidden('idDico');
        $idDico->setValue($options["idDico"]);
        
      	$valeur = new Zend_Form_Element_Textarea('valeur');
      	$valeur->setRequired(true);
		$valeur->setLabel('Définir une valeur');
      	
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
        
		$dbDico = new Model_DbTable_Dicos();			        
        
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $idDico, $valeur, $envoyer));
    }
        
}
