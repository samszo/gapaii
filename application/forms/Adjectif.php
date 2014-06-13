<?php
class Form_Adjectif extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('adjectif');

        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue($options["id"]);
                
        $eli = new Zend_Form_Element_Radio('elision', array(
		    'multiOptions' => array(0=>"sans",1=>"avec")));
      	$eli->setRequired(true);
		$eli->setLabel('Définir une élision');
        		
      	
      	$pref = new Zend_Form_Element_Text('prefix');
      	$pref->setRequired(true);
		$pref->setLabel('Définir un préfixe');

      	$ms = new Zend_Form_Element_Text('m_s');
      	$ms->setRequired(true);
		$ms->setLabel("Définir l'accord masculin singulier");
		
      	$fs = new Zend_Form_Element_Text('f_s');
      	$fs->setRequired(true);
		$fs->setLabel("Définir l'accord féminin singulier");
				
      	$mp = new Zend_Form_Element_Text('m_p');
      	$mp->setRequired(true);
		$mp->setLabel("Définir l'accord masculin pluriel");
				
      	$fp = new Zend_Form_Element_Text('f_p');
      	$fp->setRequired(true);
		$fp->setLabel("Définir l'accord féminin pluriel");
				
		$envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
		$this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array($id, $eli, $pref, $ms, $fs, $mp, $fp, $envoyer));
    }
}
