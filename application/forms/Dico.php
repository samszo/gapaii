<?php
class Form_Dico extends ZendX_JQuery_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('dico');

        $id = new Zend_Form_Element_Hidden('id');

      	$nom = new Zend_Form_Element_Text('nom');
      	$nom->setRequired(true);
		$nom->setLabel('Donner un nom:');
        
		$file = new Zend_Form_Element_File('url');
		$file->setLabel('Choisir un fichier:')
              ->setValueDisabled(true);        

		//construction des types de dico
        $config = new Zend_Config_Xml(APPLICATION_PATH.'/configs/LangageDescripteur.xml', 'dicos');
		$arrT = array();
		foreach($config->dico as $d){
			$arrT[$d->type]=$d->type;      
		}     
        $type = new Zend_Form_Element_Radio('type', array(
		    'multiOptions' => $arrT));
        $type->setLabel('Définir un type');

                
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
 
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $nom, $file, $type, $envoyer));


        
    }
}
