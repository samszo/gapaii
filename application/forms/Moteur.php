<?php
class Form_Moteur extends Zend_Form
{
    public function __construct($options = null)
    {
    	try{
	        parent::__construct($options);
	        $this->setMethod('get');
	        $this->setName('moteur');
	
	        $CacheForm = new Zend_Form_Element_Hidden('CacheForm');
	        $CacheForm->setValue(false);
	        
	        $dbDico = new Model_DbTable_Dicos();
	
	        $cbDicoCpt = $this->getChoixDico($dbDico, "concepts", "Cpt");		
			$cbDicoDtm = $this->getChoixDico($dbDico, "déterminants", "Dtm");		
			$cbDicoStg = $this->getChoixDico($dbDico, "syntagmes", "Stg");		
			$cbDicoPrCp = $this->getChoixDico($dbDico, "pronoms_complement", "PrCp");		
			$cbDicoCjg = $this->getChoixDico($dbDico, "conjugaisons", "Cjg");		
			$cbDicoPrSt = $this->getChoixDico($dbDico, "pronoms_sujet", "PrSt");		
			$cbDicoNgt = $this->getChoixDico($dbDico, "négations", "Ngt");		
			
			$valeur = new Zend_Form_Element_Textarea('valeur');
	      	$valeur->setRequired(true);
			$valeur->setLabel('Définir une valeur à générer');
	      	
	        $envoyer = new Zend_Form_Element_Submit('envoyer');
	        $envoyer->setAttrib('id', 'boutonenvoyer');

			$cbForceCalcul = new Zend_Form_Element_Checkbox('ForceCalcul');
			$cbForceCalcul->setLabel('force le calcul ');		

			$TempsMax = new Zend_Form_Element_Text('TempsMax');
			$TempsMax->setLabel('Temps max');		
			
	        $this->setAttrib('enctype', 'multipart/form-data');
	        $this->addElements(array($id, $CacheForm, $cbDicoDtm, $cbDicoNgt, $cbDicoPrSt, $cbDicoCjg, $cbDicoPrCp, $cbDicoStg, $cbDicoCpt, $valeur, $cbForceCalcul, $TempsMax, $envoyer));

    	}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	        
    }
    
    function getChoixDico($dbDico, $type, $nom){
		
    	try{
	    	$arrDicos = $dbDico->obtenirDicoType($type);
	    	$multiOptions = array();
	    	foreach ($arrDicos as $dico) {
			    $multiOptions[$dico["id_dico"]] = $dico["nom"];
			}
			$mcbDico = new Zend_Form_Element_MultiCheckbox('dicoIds'.$nom, array(
			    'multiOptions' => $multiOptions
			));
			$mcbDico->setLabel('Définir les dictionnaires de '.$type);		
	    	$mcbDico->setRequired(true);
    	}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	    return $mcbDico;
		
    }
    
}
