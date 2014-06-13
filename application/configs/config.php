<?php
date_default_timezone_set('Europe/Paris');
ini_set("memory_limit",'1600M');

$www = "/Applications/XAMPP/xamppfiles/htdocs";

define ("WEB_ROOT","http://localhost/generateur");
define ("ROOT_PATH", $www."/generateur");
define ("WEB_ROOT_AJAX",WEB_ROOT."/public");


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_PATH . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


set_include_path(ROOT_PATH.'/library');       
set_include_path(get_include_path().PATH_SEPARATOR.$www."/Zend/library");
set_include_path(get_include_path().PATH_SEPARATOR.$www."/Zend/extras/library");

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// Désactivez le plugin ErrorHandler :
//$front = Zend_Controller_Front::getInstance();
//$front->throwExceptions(true);
// Désactivez le plugin ErrorHandler :
//$front->setParam('noErrorHandler', true);        
?>