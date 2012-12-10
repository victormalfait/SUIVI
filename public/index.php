<?php 

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined ('LIBRARY_PATH') || define('LIBRARY_PATH', 
    realpath(dirname(__FILE__) .'/../library'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined ('MODELS_PATH') || define('MODELS_PATH',
   realpath(APPLICATION_PATH. '/models'));

defined ('FORMS_PATH') || define('FORMS_PATH',
   realpath(APPLICATION_PATH. '/forms'));

// on modifie l'include path de php
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(MODELS_PATH),
    realpath(FORMS_PATH),
    get_include_path(),
)));

// on a besoin de zend app pour lancer l'application
require_once 'Zend/Application.php';

// on lance la session
require_once 'Zend/Session.php';
Zend_Session::start();

// on crée l'application, on lance le bootstrap et l'application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/application.ini'
);

Zend_Registry::set('config', new Zend_Config($application->getBootstrap()->getOptions()));
$application->bootstrap()
            ->run();

