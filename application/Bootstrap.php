<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	
	public function run()
	{
		Zend_Registry::set('showmenu',true);
		parent::run();
	}
	
	protected function _initConfig()
	{
		Zend_Registry::set('config',new Zend_Config($this->getOptions()));
	}
	
	protected function _initLoaderFestival()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Projet_');
		$autoloader->setFallbackAutoloader(true);
	}

		protected function _initDb()
	{
		$db = Zend_Db::factory(Zend_Registry::get('config')->database);
		//$db->query('SET NAMES UTF8');
		//$db->setFetchMode(Zend_Db::FETCH_OBJ);;
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db',$db);

	}

	    /** Initialisation de l'empilage des actions */
    protected function _initActionStack()
    {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack(new Zend_Controller_Request_Simple('login', 'index', 'default'));
        return $actionStack;
    }

    protected function _initMail(){
    	$transport = new Zend_Mail_Transport_Smtp('smtp.free.fr');
    	Zend_Mail::setDefaultTransport($transport);
    }

	
	
}
