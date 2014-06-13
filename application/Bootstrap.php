<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
	{
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH));
		
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace(array('Gen_', "Flux_", 'Auth_'));

	    //pour pouvoir charger les classe à la fois dans le serveur amf et avec l'autoloader
		$moduleLoader->addResourceType('dbgen', 'Model/DbTable', 'Model_DbTable');
		
		return $moduleLoader;
	}

	protected function _initViewHelpers()
	{
	    $this->bootstrap('layout');
	    $layout = $this->getResource('layout');
	    $view = $layout->getView();
	    $view->doctype('XHTML1_STRICT');
	    $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
	    $view->headTitle()->setSeparator(' - ');
	    $view->headTitle('Generateur');
	    
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);	    
	}	

}

