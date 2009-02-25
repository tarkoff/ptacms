<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: index.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
//var_dump($_REQUEST);
error_reporting(E_ALL);
ini_set('dipslay_errors', 1);

require_once './config/bootstrap.inc';

class adminApp extends PTA_App 
{    
    function __construct ()
    {
        parent::__construct('app', 'Index.tpl');
    	
        $this->_router = Zend_Registry::get('router');
        
        $this->insertModules();
    }
    
    public function insertModules()
    {
    	$controller = $this->_router->getActiveController();
    	$this->_controller = empty($controller) ? 'Categories' : $controller;
    	$this->_action = $this->_router->getActiveAction();
//var_dump($this->_router);    	
    	$this->insertModule('activeModule', $this->getController());
        $this->insertModule('Header', 'Header');   
    }
    
    public function getController()
    {
        return $this->_controller;
    }
    
    public function getAction()
    {
        return $this->_action;
    }
    
    public function getQueryVar($key)
    {
        return $this->_router->getQueryVar($key);
    }
    
    public function getItem()
    {
    	return $this->_router->getQueryVar('Item');
    }
    
}

$app = new adminApp();

$app->init();
$app->run();
$app->shutdown();
