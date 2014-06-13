<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
	    $this->view->title = "Mes Dictionnaires";
	    $this->view->headTitle($this->view->title, 'PREPEND');
	    $dicos = new Model_DbTable_Dicos();
	    $select = $dicos->select()->order(array('nom'));	    
	    $this->view->dicos = $dicos->fetchAll($select);		 
    	
		//pour le débuggage
		// 
		//$this->modifierAction();
		$moteur = new Gen_Moteur("",true);
		$moteur->showErr = true;
		//dico capture
		//twitter = 96
		//chanson = 72
		//bios = 93	
		//critique = 82
		$arrDicos = array(
			"concepts"=>"34,96"
			,"syntagmes"=>4
			,"pronoms_complement"=>13
			,"conjugaisons"=>44
			,"pronoms"=>"13,14"
			,"déterminants"=>46
			,"negations"=>16);
		/*
		//dico herbarius anglais
		$arrDicos = array(
			"concepts"=>"42,75"
			,"syntagmes"=>41
			,"pronoms_complement"=>13
			,"conjugaisons"=>40
			,"pronoms"=>"38,14"
			,"déterminants"=>39
			,"negations"=>16);
		//
		//herbarius espagnol
		$arrDicos = array(
		"concepts"=>"73,86"
		,"syntagmes"=>68
		,"pronoms_complement"=>13
		,"conjugaisons"=>67
		,"pronoms"=>"70,14"
		,"déterminants"=>69
		,"negations"=>16);
		*/	
		$moteur->arrDicos = $arrDicos;	
		//$moteur->typeChoix = "alea";	
		//$moteur->Generation("[dis-sujet][v_vivre] [106#] [12|m_musique  2]");
		//$moteur->Verification("[thl-peau-01]");
		
		//
		$dico = new Gen_Dico();
		//$dico->GetMacToXml(31);		
		//$dico->SaveBdd(100,44,99);
		//$dbD = new Model_DbTable_Dicos();
	    //$dbD->supprimerDico(8);
    }

    public function modifierAction()
    {
    try{
		$type = $this->_getParam('type', 0);
        $id = $this->_getParam('id', 0);
        $idParent = $this->_getParam('idParent', 0);
        $this->view->idParent = $id;
                
        /*
		$type = 'concept';//$this->_getParam('type', 0);
        $id = '61629'; //$this->_getParam('id', 0);
        $idParent = '37';//$this->_getParam('idParent', 0);
        $this->view->idParent = $id;

        $echo =false;
        Zend_Debug::dump($id, $echo, $echo);
        Zend_Debug::dump($type, $label = null, $echo = true);
		*/
        
        if($type=='dico'){
	        $table = new Model_DbTable_Dicos();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$form = new Form_DicoModif();
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
			if($parent['type']=='conjugaisons'){
				$DB_ = new Model_DbTable_Conjugaisons();
				$select = $DB_->select()->order(array('modele'));
				$enfants = $parent->findDependentRowset('Model_DbTable_Conjugaisons','Dico',$select);
				$types = array("parent"=>"dico","enfant"=>"conjugaison");	            	
				$this->view->libAjout = "Ajouter une nouvelle conjugaison";
			}
			if($parent['type']=='déterminants'){
				$enfants = $parent->findDependentRowset('Model_DbTable_Determinants');
				$types = array("parent"=>"dico","enfant"=>"determinant");	            	
				$this->view->libAjout = "Ajouter un nouveau déterminant";
			}
			if($parent['type']=='compléments'){
				$enfants = $parent->findDependentRowset('Model_DbTable_Complements');
				$types = array("parent"=>"dico","enfant"=>"complement");	            	
				$this->view->libAjout = "Ajouter un nouveau complément";
			}
			if($parent['type']=='syntagmes'){
				$DB_ = new Model_DbTable_Syntagmes();
				$select = $DB_->select()->order(array('lib'));
				$enfants = $parent->findDependentRowset('Model_DbTable_Syntagmes','Dico',$select);
				$types = array("parent"=>"dico","enfant"=>"DicoSyntagme");	            	
				$this->view->libAjout = "Ajouter un nouveau syntagme";
			}
			if($parent['type']=='concepts'){
				$DB_ = new Model_DbTable_Concepts();
				$select = $DB_->select()->order(array('type','lib'));
				$enfants = $parent->findDependentRowset('Model_DbTable_Concepts','Dico',$select);
				$types = array("parent"=>"dico","enfant"=>"concept");	            	
				$this->view->libAjout = "Ajouter un nouveau concept";
			}
			if($parent['type']=='pronoms_complement'){
				$enfants = $parent->findDependentRowset('Model_DbTable_Pronoms');
				$types = array("parent"=>"dico","enfant"=>"pronom_complement");	            	
				$this->view->libAjout = "Ajouter un nouveau pronom complèment";
			}
			if($parent['type']=='pronoms_sujet'){
				$enfants = $parent->findDependentRowset('Model_DbTable_Pronoms');
				$types = array("parent"=>"dico","enfant"=>"pronom_sujet");	            	
				$this->view->libAjout = "Ajouter un nouveau pronom sujet";
			}
			if($parent['type']=='négations'){
				$enfants = $parent->findDependentRowset('Model_DbTable_Negations');
				$types = array("parent"=>"dico","enfant"=>"negation");	            	
				$this->view->libAjout = "Ajouter une nouvelle négation";
			}
			$this->view->title = "Modification du ".$type." (".$id.")";
        }
        if($type=='conjugaison'){
	        $table = new Model_DbTable_Conjugaisons();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$enfants = $parent->findDependentRowset('Model_DbTable_Terminaisons');
			$types = array("parent"=>"conjugaison","enfant"=>"terminaison");
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Conjugaison(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
			$this->view->title = "Modification de la ".$type." (".$id.")";
			$this->view->libAjout = "Ajouter une nouvelle terminaison";
        }
        if($type=='terminaison'){
	        $table = new Model_DbTable_Terminaisons();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>"terminaison");	            	
			$this->view->title = "Modification de la ".$type." (".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Terminaison(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='determinant'){
	        $table = new Model_DbTable_Determinants();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>"determinant");	            	
			$this->view->title = "Modification du déterminant (".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Determinant(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='complement'){
	        $table = new Model_DbTable_Complements();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>"complement");	            	
			$this->view->title = "Modification du complément (".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Complement(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='DicoSyntagme'){
	        $table = new Model_DbTable_Syntagmes();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>"DicoSyntagme");	            	
			$this->view->title = "Modification du syntagme (".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Syntagme(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='concept'){
	        $table = new Model_DbTable_Concepts();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			if(!is_numeric($parent->type) && $parent->type!="" && $parent->type!="age" && $parent->type!="thl" && $parent->type!="univers"){
				//charge les enfants suivant le type de concept
				if($parent->type=="dis" || $parent->type=="carac"){
					$tType="Substantifs";
					$enfants = $parent->findManyToManyRowset('Model_DbTable_'.$tType,
	                                                 'Model_DbTable_Concepts'.$tType);
					$tType1="Adjectifs";
					$enfants1 = $parent->findManyToManyRowset('Model_DbTable_'.$tType1,
	                                                 'Model_DbTable_Concepts'.$tType1);
				}else{
					if($parent->type=="a")$tType="Adjectifs";
					if($parent->type=="v")$tType="Verbes";
					if($parent->type=="m")$tType="Substantifs";
					if($parent->type=="s")$tType="Syntagmes";
					$enfants = $parent->findManyToManyRowset('Model_DbTable_'.$tType,
		                                                 'Model_DbTable_Concepts'.$tType);
				}
			}
			//ajout des généreteurs
			$gens = $parent->findManyToManyRowset('Model_DbTable_Generateurs',
	                                                 'Model_DbTable_ConceptsGenerateurs');
			$this->view->gens = $gens;
			$types = array("parent"=>"concept","enfant"=>strtolower(substr($tType,0,-1)),"enfant1"=>strtolower(substr($tType1,0,-1)));	            	
			$this->view->title = "Modification du concept (".$id.")";
			$this->view->libAjout = "Ajouter ".$types["enfant"];
			//ajout du formulaire pour modifier l'élément parent
			$form = new Form_Concept(array("id"=>$id,"idDico"=>$parent->id_dico));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='adjectif'){
	        $table = new Model_DbTable_Adjectifs();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$this->view->idParent=$idParent;
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification de l'adjectif (".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Adjectif(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='verbe'){
	        $table = new Model_DbTable_Verbes();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$this->view->idParent=$idParent;
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification du verbe (".$id.")";
			$this->view->libAjout = "";
        	$form = new Form_Verbe(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='generateur'){
	        $table = new Model_DbTable_Generateurs();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$this->view->idParent=$idParent;
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification du generateur (".$id.")";
			$this->view->libAjout = "";
			//ajout du formulaire pour modifier l'élément parent
        	$form = new Form_Generateur(array("id"=>$id,"idDico"=>$parent->id_dico));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='substantif'){
	        $table = new Model_DbTable_Substantifs();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$this->view->idParent=$idParent;
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification du substantif (".$id.")";
			$this->view->libAjout = "";
			//ajout du formulaire pour modifier l'élément parent
        	$form = new Form_Substantif(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='syntagme'){
	        $table = new Model_DbTable_Syntagmes();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$this->view->idParent=$idParent;
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification du syntagme (".$id.")";
			$this->view->libAjout = "";
			//ajout du formulaire pour modifier l'élément parent
        	$form = new Form_Syntagme(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='pronom_complement'){
	        $table = new Model_DbTable_Pronoms();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification du pronom complement(".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Pronom(array("id"=>$id,"type"=>"complement"));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='pronom_sujet'){
	        $table = new Model_DbTable_Pronoms();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification du pronom sujet(".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Pronom(array("id"=>$id,"type"=>"complement"));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        if($type=='negation'){
	        $table = new Model_DbTable_Negations();
			$Rowset = $table->find($id);
			$parent = $Rowset->current();
			$types = array("parent"=>$type);	            	
			$this->view->title = "Modification de la négation (".$id.")";
			$this->view->libAjout = "";
		    //ajout du formulaire pour modifier l'élément parent
			$form = new Form_Negation(array("id"=>$id));
		    $form->envoyer->setLabel('Modifier');
	        $form->populate($parent->toArray());
		    $this->view->form = $form;		    
        }
        
        if(count($enfants)>0){
			$this->view->cols = $enfants->getTable()->info('cols');
	        $this->view->key = $enfants->getTable()->info('primary');
	    }
        if(count($enfants1)>0){
			$this->view->cols1 = $enfants1->getTable()->info('cols');
	        $this->view->key1 = $enfants1->getTable()->info('primary');
	    }
	    $this->view->headTitle($this->view->title, 'PREPEND');
		$this->view->parent = $parent;
		$this->view->enfants = $enfants;
		$this->view->enfants1 = $enfants1;
		$this->view->types = $types;
		
		if ($this->getRequest()->isPost()) {
	        $formData = $this->getRequest()->getPost();
	        if ($form->isValid($formData)) {
	        	if($type=='dico'){
					$dbDic = new Model_DbTable_Dicos();
					$dbDic->modifierDico($form->getValue('id_dico'),$form->getValue('nom'),$form->getValue('langue'),$form->getValue('url'),$form->getValue('type'),$form->getValue('url_source'));
	        	}
	        	if($type=="conjugaison"){
					$dbCo = new Model_DbTable_Conjugaisons();
					$dbCo->modifierConjugaison($form->getValue('id'),$form->getValue('num'),$form->getValue('modele'));
	        	}
	        	if($type=="terminaison"){
					$dbT = new Model_DbTable_Terminaisons();
					$dbT->modifierTerminaison($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'));
	        	}
	        	if($type=="determinant"){
					$dbD = new Model_DbTable_Determinants();
					$dbD->modifierDeterminant($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
	        	}
	        	if($type=="complement"){
					$dbC = new Model_DbTable_Complements();
					$dbC->modifierComplement($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
	        	}
	        	if($type=="DicoSyntagme"){
					$dbS = new Model_DbTable_Syntagmes();
					$dbS->modifierSyntagme($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
	        	}
	        	if($type=="concept"){
					$dbCpt = new Model_DbTable_Concepts();
					$dbCpt->modifierConcept($form->getValue('id'),$form->getValue('lib'),$form->getValue('type'));
	        	}
	        	if($type=="adjectif"){
					$dbAdj = new Model_DbTable_Adjectifs();
					$dbAdj->modifierAdjectif($form->getValue('id'),$form->getValue('elision'),$form->getValue('prefix'),$form->getValue('m_s'),$form->getValue('f_s'),$form->getValue('m_p'),$form->getValue('f_p'));
	        	}
	        	if($type=="verbe"){
					$dbVer = new Model_DbTable_Verbes();
					$dbVer->modifierVerbe($form->getValue('id'),$form->getValue('id_conj'),$form->getValue('elision'),$form->getValue('prefix'),$form->getValue('modele'));
	        	}
	        	if($type=="generateur"){
					$dbGen = new Model_DbTable_Generateurs();
					$dbGen->modifierGenerateur($form->getValue('id'),$form->getValue('valeur'));
	        	}
	        	if($type=="substantif"){
					$dbSub = new Model_DbTable_Substantifs();
					$dbSub->modifierSubstantif($form->getValue('id'),$form->getValue('elision'),$form->getValue('prefix'),$form->getValue('s'),$form->getValue('p'),$form->getValue('genre'));
	        	}
	        	if($type=="syntagme"){
					$dbSyn = new Model_DbTable_Syntagmes();
					$dbSyn->modifierSyntagme($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
	        	}
	        	if($type=="pronom_complement"){
					$dbPro = new Model_DbTable_Pronoms();
					$dbPro->modifierPronom($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'),$form->getValue('lib_eli'),$form->getValue('type'));
	        	}
	        	if($type=="pronom_sujet"){
					$dbPro = new Model_DbTable_Pronoms();
					$dbPro->modifierPronom($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'),$form->getValue('lib_eli'),$form->getValue('type'));
	        	}
	        	if($type=="negation"){
					$dbNeg = new Model_DbTable_Negations();
					$dbNeg->modifierNegation($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'));
	        	}
	        	$this->_redirect('/index/modifier/type/'.$type.'/id/'.$id.'/idParent/'.$idParent);
	        }else{
	            $form->populate($formData);
	        }
        }		
	}catch (Zend_Exception $e) {
          // Appeler Zend_Loader::loadClass() sur une classe non-existante
          //entrainera la levée d'une exception dans Zend_Loader
          echo "Récupère exception: " . get_class($e) . "\n";
          echo "Message: " . $e->getMessage() . "\n";
          // puis tout le code nécessaire pour récupérer l'erreur
	}
        
		
    }
    
    public function sauvegarderAction()
    {
    	try{
	    	$this->view->title = "Sauvegarde du dictionaire XML dans la BDD";
		    $this->view->headTitle($this->view->title, 'PREPEND');
	
		    if ($this->getRequest()->isPost()) {
		        $calculer = $this->getRequest()->getPost('sauvegarder');
		        if ($calculer == 'Oui') {
		            $id = $this->getRequest()->getPost('id');
		            $idDicoConj = $this->getRequest()->getPost('idDicoConj');
		            $idDicoMerge = $this->getRequest()->getPost('idDicoMerge');
		            echo "idDico = ".$id." idDicoConj = ".$idDicoConj." idDicoMerge = $idDicoMerge<br/>";
    		set_time_limit(600); 
		            $dico = new Gen_Dico();
					$dico->SaveBdd($id, $idDicoConj,$idDicoMerge);
		        	$this->_redirect('/index/modifier/id/'.$id.'/type/dico');
		        }else{
		        	$this->_redirect('/');
		        }
		    } else {
		        $id = $this->_getParam('id', 0);
		        $dicos = new Model_DbTable_Dicos();
		        $this->view->dico = $dicos->obtenirDico($id);
		    }
		}catch (Zend_Exception $e) {
	          // Appeler Zend_Loader::loadClass() sur une classe non-existante
	          //entrainera la levée d'une exception dans Zend_Loader
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
	          // puis tout le code nécessaire pour récupérer l'erreur
		}
   	}
    
    public function creerxmlAction()
    {
		$this->view->title = "Création du dictionnaire au format XML";
	    $this->view->headTitle($this->view->title, 'PREPEND');

	    if ($this->getRequest()->isPost()) {
	        $calculer = $this->getRequest()->getPost('calculer');
	        if ($calculer == 'Oui') {
	            $id = $this->getRequest()->getPost('id');
	            //echo "idDico = ".$id."<br/>";
			    $dico = new Gen_Dico();
				$dico->GetMacToXml($id);
				//$this->view->xml = $dico->xml->saveHTML();
	        	$this->_redirect('index/sauvegarder/id/'.$id);
	        }else{
	        	$this->_redirect('/');
	        }
	    } else {
	        $id = $this->_getParam('id', 0);
	        $dicos = new Model_DbTable_Dicos();
	        $this->view->dico = $dicos->obtenirDico($id);
	    }
    }
    
    public function ajouterAction()
    {
		$type = $this->_getParam('type', 0);
        $id = $this->_getParam('id', 0);
        $idParent = $this->_getParam('idParent', 0);
        $this->view->idParent = $id;
                
	    $this->view->headTitle($this->view->title, 'PREPEND');
			    
	    if($type=="dico"){
		    $form = new Form_Dico();
			$this->view->types = array("parent"=>"dico");	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
    	}
		if($type=="conjugaison"){
		    $form = new Form_Conjugaison(array("id"=>$id));
			$dicos = new Model_DbTable_Dicos();
	        $this->view->parent = $dicos->obtenirDico($id);
			$this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter une nouvelle ".$type;
		}
		if($type=="terminaison"){
		    $form = new Form_Terminaison(array("id"=>$id));
			$conjs = new Model_DbTable_Conjugaisons();
	        $this->view->parent = $conjs->obtenirConjugaison($id);
			$this->view->types = array("parent"=>"conjugaison","enfant"=>$type);	            	
        	$this->view->title = "Ajouter une nouvelle ".$type;
		}
		if($type=="determinant"){
		    $form = new Form_Determinant(array("id"=>$id));
			$dicos = new Model_DbTable_Dicos();
	        $this->view->parent = $dicos->obtenirDico($id);
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
		}
		if($type=="complement"){
		    $form = new Form_Complement(array("id"=>$id));
			$dicos = new Model_DbTable_Dicos();
	        $this->view->parent = $dicos->obtenirDico($id);
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
		}
		if($type=="DicoSyntagme"){
		    $form = new Form_Syntagme(array("id"=>$id));
			$dicos = new Model_DbTable_Dicos();
	        $this->view->parent = $dicos->obtenirDico($id);
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
		}
		if($type=="concept"){
		    $form = new Form_Concept(array("id"=>$id));
			$dicos = new Model_DbTable_Dicos();
	        $this->view->parent = $dicos->obtenirDico($id);
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
		}
		if($type=="adjectif"){
		    $form = new Form_Adjectif(array("id"=>$id));
			$dbCpt = new Model_DbTable_Concepts();
	        $this->view->parent = $dbCpt->obtenirConcept($id);
		    $this->view->types = array("parent"=>"concept","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouvel ".$type;
		}
		if($type=="verbe"){
			$dbCpt = new Model_DbTable_Concepts();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"concept","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
			$dbConj = new Model_DbTable_Conjugaisons();
			$RsConjs = $dbConj->obtenirConjugaisonListeModeles($parent['id_dico']);
        	$form = new Form_Verbe(array("id"=>$id,"RsConjs"=>$RsConjs));
		}
		if($type=="generateur"){
			$dbCpt = new Model_DbTable_Concepts();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"concept","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
        	$form = new Form_Generateur(array("id"=>$id));
		}
		if($type=="substantif"){
			$dbCpt = new Model_DbTable_Concepts();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"concept","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
        	$form = new Form_Substantif(array("id"=>$id));
		}
		if($type=="syntagme"){
			$dbCpt = new Model_DbTable_Concepts();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"concept","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau ".$type;
        	$form = new Form_Syntagme(array("id"=>$id));
		}
		if($type=="pronom_complement"){
			$dbCpt = new Model_DbTable_Pronoms();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau pronom complément";
        	$form = new Form_Pronom(array("id"=>$id,"type"=>"complement"));
		}
		if($type=="pronom_sujet"){
			$dbCpt = new Model_DbTable_Pronoms();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter un nouveau pronom sujet";
        	$form = new Form_Pronom(array("id"=>$id,"type"=>"sujet"));
		}
		if($type=="negation"){
			$dbCpt = new Model_DbTable_Negations();
			$Rowset = $dbCpt->find($id);
			$parent = $Rowset->current();
	        $this->view->parent = $parent;
		    $this->view->types = array("parent"=>"dico","enfant"=>$type);	            	
        	$this->view->title = "Ajouter une nouvelle négation";
        	$form = new Form_Negation(array("id"=>$id));
		}
		
	    $form->envoyer->setLabel('Ajouter');
	    $this->view->form = $form;
	
	    if ($this->getRequest()->isPost()) {
	        $formData = $this->getRequest()->getPost();
	        if ($form->isValid($formData)) {

	        	if($type=="dico"){
	        		$this->ajouterDico($form);
	        	}
	        	if($type=="conjugaison"){
					$db = new Model_DbTable_Conjugaisons();
					$db->ajouterConjugaison($form->getValue('id'),$form->getValue('num'),$form->getValue('modele'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        	if($type=="terminaison"){
					$db = new Model_DbTable_Terminaisons();
					$db->ajouterTerminaison($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'));
					$this->_redirect('/index/modifier/type/conjugaison/id/'.$id);
	        	}
	        	if($type=="determinant"){
					$dbD = new Model_DbTable_Determinants();
					$dbD->ajouterDeterminant($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        	if($type=="complement"){
					$dbC = new Model_DbTable_Complements();
					$dbC->ajouterComplement($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        	if($type=="DicoSyntagme"){
					$dbS = new Model_DbTable_Syntagmes();
					$dbS->ajouterSyntagme($form->getValue('id'),$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        	if($type=="concept"){
					$dbCpt = new Model_DbTable_Concepts();
					$idNew = $dbCpt->ajouterConcept($form->getValue('id'),$form->getValue('lib'),$form->getValue('type'));
					$this->_redirect('/index/modifier/type/concept/id/'.$idNew.'/idParent/'.$id);
	        	}
	        	if($type=="adjectif"){
					$dbCpt = new Model_DbTable_Concepts();
	        		$rs = $dbCpt->find($form->getValue('id'));
					$cpt = $rs->current();

					$dbAdj = new Model_DbTable_Adjectifs();
					$idAdj = $dbAdj->ajouterAdjectif($cpt['id_dico'],$form->getValue('elision'),$form->getValue('prefix'),$form->getValue('m_s'),$form->getValue('f_s'),$form->getValue('m_p'),$form->getValue('f_p'));

					$dbCptAdj = new Model_DbTable_ConceptsAdjectifs();
					$dbCptAdj->ajouterConceptAdjectif($cpt['id_concept'], $idAdj);

					$this->_redirect('/index/modifier/type/concept/id/'.$id);
	        	}
	        	if($type=="verbe"){
					$dbCpt = new Model_DbTable_Concepts();
	        		$rs = $dbCpt->find($form->getValue('id'));
					$cpt = $rs->current();

					$dbVer = new Model_DbTable_Verbes();
					$idVer = $dbVer->ajouterVerbe($cpt['id_dico'],$form->getValue('id_conj'),$form->getValue('elision'),$form->getValue('prefix'));

					$dbCptVer = new Model_DbTable_ConceptsVerbes();
					$dbCptVer->ajouterConceptVerbe($cpt['id_concept'], $idVer);

					$this->_redirect('/index/modifier/type/concept/id/'.$id);
	        	}
	        	if($type=="generateur"){
					$dbCpt = new Model_DbTable_Concepts();
	        		$rs = $dbCpt->find($form->getValue('id'));
					$cpt = $rs->current();

					$dbGen = new Model_DbTable_Generateurs();
					$idGen = $dbGen->ajouterGenerateur($cpt['id_dico'],$form->getValue('valeur'));

					$dbCptGen = new Model_DbTable_ConceptsGenerateurs();
					$dbCptGen->ajouterConceptGenerateur($cpt['id_concept'], $idGen);

					$this->_redirect('/index/modifier/type/concept/id/'.$id);
	        	}
	        	if($type=="substantif"){
					$dbCpt = new Model_DbTable_Concepts();
	        		$rs = $dbCpt->find($form->getValue('id'));
					$cpt = $rs->current();

					$dbSub = new Model_DbTable_Substantifs();
					$idSub = $dbSub->ajouterSubstantif($cpt['id_dico'],$form->getValue('elision'),$form->getValue('prefix'),$form->getValue('s'),$form->getValue('p'),$form->getValue('genre'));

					$dbCptSub = new Model_DbTable_ConceptsSubstantifs();
					$dbCptSub->ajouterConceptSubstantif($cpt['id_concept'], $idSub);

					$this->_redirect('/index/modifier/type/concept/id/'.$cpt['id_concept'].'/idParent/'.$id);
	        	}
	        	if($type=="syntagme"){
					$dbCpt = new Model_DbTable_Concepts();
	        		$rs = $dbCpt->find($form->getValue('id'));
					$cpt = $rs->current();

					$dbSyn = new Model_DbTable_Syntagmes();
					$idSyn = $dbSyn->ajouterSyntagme($cpt['id_dico'],$form->getValue('num'),$form->getValue('ordre'),$form->getValue('lib'));

					$dbCptSyn = new Model_DbTable_ConceptsSyntagmes();
					$dbCptSyn->ajouterConceptSyntagme($cpt['id_concept'], $idSyn);

					$this->_redirect('/index/modifier/type/concept/id/'.$id);
	        	}
	        	if($type=="pronom_complement"){
					$dbPro = new Model_DbTable_Pronoms();
					$dbPro->ajouterPronom($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'),$form->getValue('lib_eli'),$form->getValue('type'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        	if($type=="pronom_sujet"){
					$dbPro = new Model_DbTable_Pronoms();
					$dbPro->ajouterPronom($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'),$form->getValue('lib_eli'),$form->getValue('type'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        	if($type=="negation"){
					$dbNeg = new Model_DbTable_Negations();
					$dbNeg->ajouterNegation($form->getValue('id'),$form->getValue('num'),$form->getValue('lib'));
					$this->_redirect('/index/modifier/type/dico/id/'.$id);
	        	}
	        }else{
	            $form->populate($formData);
	        }
	    }
    }

    
    private function ajouterDico($form){

	try {
    	
    	$adapter = new Zend_File_Transfer_Adapter_Http();
        //echo ROOT_PATH.'/data/upload';
    	$adapter->setDestination(ROOT_PATH.'/data/upload');
               			
		if (!$adapter->receive()) {
			$messages = $adapter->getMessages();
			echo implode("\n", $messages);
      	}else{
			// Retourne toutes les informations connues sur le fichier
			$files = $adapter->getFileInfo();
			foreach ($files as $file => $info) {
				// Les validateurs sont-ils OK ?
				if (!$adapter->isValid($file)) {
					print "Désolé mais $file ne correspond à ce que nous attendons";
					continue;
				}
				//echo "infos:".print_r($info);
			}
      	}
        $type = $form->getValue('type');
        $url = str_replace(ROOT_PATH,WEB_ROOT,$adapter->getFileName());
        //$url = str_replace("\\","/",$url);
		$dico = new Gen_Dico();
		$dico->nom = $form->getValue('nom');
        $dico->urlS = $url;
        $dico->pathS = $adapter->getFileName();
        $dico->type = $type;
        $dico->Save();
		print_r($dico);
        
        $this->_redirect('/');
	}catch (Zend_Exception $e) {
          // Appeler Zend_Loader::loadClass() sur une classe non-existante
          //entrainera la levée d'une exception dans Zend_Loader
          echo "Récupère exception: " . get_class($e) . "\n";
          echo "Message: " . $e->getMessage() . "\n";
          // puis tout le code nécessaire pour récupérer l'erreur
	}
        
    }
    
    
    public function supprimerAction()
	{
	try {
		
		$type = $this->_getParam('type', 0);
        $id = $this->_getParam('id', 0);
        $idParent = $this->_getParam('idParent', 0);
        $this->view->idParent = $id;
                
        if($type=="terminaison" || $type=="conjugaison")
			$this->view->title = "Supprimer la ".$type;
        elseif($type=="adjectif")
			$this->view->title = "Supprimer l'".$type;
		else
			$this->view->title = "Supprimer le ".$type;
		$this->view->headTitle($this->view->title, 'PREPEND');
	
	    if ($this->getRequest()->isPost()) {
	        $supprimer = $this->getRequest()->getPost('supprimer');
	        if ($supprimer == 'Oui') {
	            $id = $this->getRequest()->getPost('id');
	            if($type=="dico"){
		            $dbD = new Model_DbTable_Dicos();
		            $dbD->supprimerDico($id);	            	
	            }
	            if($type=="conjugaison"){
		            $dbConj = new Model_DbTable_Conjugaisons();
		            $dbConj->supprimerConjugaison($id);	            	
	            }
	            if($type=="terminaison"){
		            $dbT = new Model_DbTable_Terminaisons();
		            $dbT->supprimerTerminaison($id);	            	
	            }
	            if($type=="determinant"){
		            $dbDt = new Model_DbTable_Determinants();
		            $dbDt->supprimerDeterminant($id);	            	
	            }
	            if($type=="complement"){
		            $dbC = new Model_DbTable_Complements();
		            $dbC->supprimerComplement($id);	            	
	            }
	            if($type=="DicoSyntagme"){
		            $dbS = new Model_DbTable_Syntagmes();
		            $dbS->supprimerSyntagme($id);	            	
	            }
	            if($type=="concept"){
		            $dbCpt = new Model_DbTable_Concepts();
		            $dbCpt->supprimerConcept($id);	            	
	            }
	            if($type=="adjectif"){
		            $dbAdj = new Model_DbTable_Adjectifs();
		            $dbAdj->supprimerAdjectif($id);	            	
	            }
	            if($type=="verbe"){
		            $dbVer = new Model_DbTable_Verbes();
		            $dbVer->supprimerVerbe($id);	            	
	            }
	            if($type=="generateur"){
		            $dbGen = new Model_DbTable_Generateurs();
		            $dbGen->supprimerGenerateur($id);	            	
	            }
	            if($type=="substantif"){
		            $dbSub = new Model_DbTable_Substantifs();
		            $dbSub->supprimerSubstantif($id);	            	
	            }
	            if($type=="syntagme"){
		            $dbSyn = new Model_DbTable_Syntagmes();
		            $dbSyn->supprimerSyntagme($id);	            	
	            }
	            if($type=="pronom_complement"){
		            $dbPro = new Model_DbTable_Pronoms();
		            $dbPro->supprimerPronom($id);	            	
	            }
	            if($type=="pronom_sujet"){
		            $dbPro = new Model_DbTable_Pronoms();
		            $dbPro->supprimerPronom($id);	            	
	            }
	            if($type=="negation"){
		            $dbNeg = new Model_DbTable_Negations();
		            $dbNeg->ajouterNegation($id);	            	
	            }
	        }
	        if($type=="dico") $this->_redirect('/');
	        if($type=="conjugaison" || $type=="determinant" || $type=="complement" || $type=="DicoSyntagme" || $type=="concept"
	        	 || $type=="pronom_complement" || $type=="pronom_sujet" || $type=="negation")
	        	$this->_redirect('/index/modifier/type/dico/id/'.$this->_getParam('idParent', 0));
	        if($type=="terminaison") $this->_redirect('/index/modifier/type/conjugaison/id/'.$this->_getParam('idParent', 0));
	        if($type=="adjectif" || $type=="verbe" || $type=="generateur" || $type=="substantif"|| $type=="syntagme")
	        	$this->_redirect('/index/modifier/type/concept/id/'.$this->_getParam('idParent', 0));
	    } else {
            if($type=="dico"){
	            $dicos = new Model_DbTable_Dicos();
		        $this->view->parent = $dicos->obtenirDico($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
            }
            if($type=="conjugaison"){
	            $conjs = new Model_DbTable_Conjugaisons();
		        $this->view->parent = $conjs->obtenirConjugaison($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="terminaison"){
	            $terms = new Model_DbTable_Terminaisons();
		        $this->view->parent = $terms->obtenirTerminaison($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_conj"];
            }	        
            if($type=="determinant"){
	            $deter = new Model_DbTable_Determinants();
		        $this->view->parent = $deter->obtenirDeterminant($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="complement"){
	            $comp = new Model_DbTable_Complements();
		        $this->view->parent = $comp->obtenirComplement($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="DicoSyntagme"){
	            $synt = new Model_DbTable_Syntagmes();
		        $this->view->parent = $synt->obtenirSyntagme($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="concept"){
	            $cpts = new Model_DbTable_Concepts();
		        $this->view->parent = $cpts->obtenirConcept($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="adjectif"){
	            $table = new Model_DbTable_Adjectifs();
				$Rowset = $table->find($id);
				$parent = $Rowset->current();            
		        $this->view->parent = $parent;
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
				$this->view->idParent=$idParent;
            }	        
            if($type=="verbe"){
	            $table = new Model_DbTable_Verbes();
				$Rowset = $table->find($id);
				$parent = $Rowset->current();            
		        $this->view->parent = $parent;
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
				$this->view->idParent=$idParent;
            }	        
            if($type=="generateur"){
	            $table = new Model_DbTable_Generateurs();
				$Rowset = $table->find($id);
				$parent = $Rowset->current();            
		        $this->view->parent = $parent;
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
				$this->view->idParent=$idParent;
            }	        
            if($type=="substantif"){
	            $table = new Model_DbTable_Substantifs();
				$Rowset = $table->find($id);
				$parent = $Rowset->current();            
		        $this->view->parent = $parent;
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
				$this->view->idParent=$idParent;
            }	        
            if($type=="syntagme"){
	            $table = new Model_DbTable_Syntagmes();
				$Rowset = $table->find($id);
				$parent = $Rowset->current();            
		        $this->view->parent = $parent;
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
				$this->view->idParent=$idParent;
            }	        
            if($type=="pronom_complement"){
	            $Pro = new Model_DbTable_Pronoms();
		        $this->view->parent = $Pro->obtenirPronom($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="pronom_sujet"){
	            $Pro = new Model_DbTable_Pronoms();
		        $this->view->parent = $Pro->obtenirPronom($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
            if($type=="negation"){
	            $Neg = new Model_DbTable_Negations();
		        $this->view->parent = $Neg->obtenirNegation($id);
				$this->view->types = array("parent"=>$type);	            	
		        $this->view->id = $id;
		        $this->view->idParent = $this->view->parent["id_dico"];
            }	        
	    }
	}catch (Zend_Exception $e) {
          // Appeler Zend_Loader::loadClass() sur une classe non-existante
          //entrainera la levée d'une exception dans Zend_Loader
          echo "Récupère exception: " . get_class($e) . "\n";
          echo "Message: " . $e->getMessage() . "\n";
          // puis tout le code nécessaire pour récupérer l'erreur
	}
	    
	}    
}



