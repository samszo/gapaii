<?php
class Form_DicoModif extends ZendX_JQuery_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('dico');

        $id = new Zend_Form_Element_Hidden('id_dico');

      	$nom = new Zend_Form_Element_Text('nom');
      	$nom->setRequired(true);
		$nom->setLabel('Nom du dico:');
      	$langue = new Zend_Form_Element_Text('langue');
		$langue->setLabel('Langue:');
		
		$url = new Zend_Form_Element_Hidden('url');
		$type = new Zend_Form_Element_Hidden('type');
		$url_source = new Zend_Form_Element_Hidden('url_source');
		$path_source = new Zend_Form_Element_Hidden('path_source');

		$envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
 
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $nom, $langue, $url, $type, $url_source, $path_source, $envoyer));


        
    }
}
