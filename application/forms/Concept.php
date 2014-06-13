<?php
class Form_Concept extends Zend_Form
{
    public function __construct($options = null)
    {
		try {
	    	
	    	parent::__construct($options);
	        $this->setName('concept');
	
	        $id = new Zend_Form_Element_Hidden('id');
	        $id->setValue($options["id"]);

	        $idDico = new Zend_Form_Element_Hidden('idDico');
        	$idDico->setValue($options["idDico"]);
	        
	      	$lib = new Zend_Form_Element_Text('lib');
	      	$lib->setRequired(true);
			$lib->setLabel('Définir un libellé');
	        
			$type = new Zend_Form_Element_Select('type', array(
			    'multiOptions' => array("v"=>"verbe","a"=>"adjectif","m"=>"substantif","s"=>"syntagme"
					,"dis"=>"distinction","carac"=>"caractère","thl"=>"théorie","univers"=>"univers")));
	      	$type->setRequired(true);
			$type->setLabel("Choisir un type");
			
	        $envoyer = new Zend_Form_Element_Submit('envoyer');
	        $envoyer->setAttrib('id', 'boutonenvoyer');
			$this->setAttrib('enctype', 'multipart/form-data');
	        $this->addElements(array($id, $idDico, $lib, $type, $envoyer));
		
		}catch (Zend_Exception $e) {
	          // Appeler Zend_Loader::loadClass() sur une classe non-existante
	          //entrainera la levée d'une exception dans Zend_Loader
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
	          // puis tout le code nécessaire pour récupérer l'erreur
		}
	}
}
