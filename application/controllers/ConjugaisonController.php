<?php
class ConjugaisonController extends Zend_Controller_Action
{

    public function conjugaisonAction()
    {
    	echo "TOTO";
		testerAction();		
    }
    
    public function testerAction()
    {
    try{
        $form = new Form_Verbe(array("id"=>-1));
	    $form->envoyer->setLabel('Tester');
	    $this->view->form = $form;

	    if ($this->getRequest()->isPost()) {
	        $formData = $this->getRequest()->getPost();
	        if ($form->isValid($formData)) {
	        	//récupère les terminaisons du modèle
				$dbTerms = new Model_DbTable_Terminaisons();
				$this->view->Terms = $dbTerms->obtenirConjugaison($form->getValue('id_conj'));
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

