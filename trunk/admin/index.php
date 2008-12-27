<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

error_reporting(E_ALL);
ini_set('dipslay_errors', 1);

require_once 'config/bootstrap.inc';

class adminApp extends PTA_App 
{
    private $_controler = 'Categories';
    private $_model; 
    private $_action = 'List';
    private $_item;
    
    /**
     * 
     */
    function __construct ()
    {
        parent::__construct('app', 'Index.tpl');
        
        $this->insertModules();
    }
    
    public function insertModules()
    {
        $this->detectControler();
        $this->detectModel();        
        $this->detectAction();
        $this->detectItem();
        
        $this->insertModule('activeModule', $this->getControler());
        $this->insertModule('Header', 'Header');
        
    }

    public function detectControler()
    {
        if (isset($_GET['Controler']) && !empty($_GET['Controler'])) {
        	$this->_controler = ucfirst($_GET['Controler']);
        }
        
    }

    public function detectAction()
    {
        if (isset($_GET['Action']) && !empty($_GET['Action'])) {
        	$this->_action = ucfirst($_GET['Action']);
        }
        
    }
    
    public function detectModel()
    {
        if (isset($_GET['Model']) && !empty($_GET['Model'])) {
        	$this->_brand = ucfirst($_GET['Model']);
        }
        
    }
    
    public function detectItem()
    {
        if (isset($_GET['Item']) && !empty($_GET['Item'])) {
        	$this->_item = ucfirst($_GET['Item']);
        }
        
    }
    
    public function getControler()
    {
        return $this->_controler;
    }
    
    public function getAction()
    {
        return $this->_action;
    }
    
    public function getModel()
    {
        return $this->_model;
    }
    
    public function getItem()
    {
        return $this->_item;
    }
    
}

$app = new adminApp();

$app->init();
$app->run();
$app->shutdown();

