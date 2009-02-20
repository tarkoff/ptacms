<?php

error_reporting(E_ALL);
ini_set('dipslay_errors', 1);

require_once 'app/site/config/bootstrap.inc';

class Site_App extends PTA_App 
{
    private $_controler = 'Main';
    private $_action = 'Index'; 
    private $_brand;
    private $_model;
    

    public function __construct()
    {
        parent::__construct('app', 'Index.tpl');
        
        $this->insertModules();
    }
    
    public function insertModules()
    {
        $this->detectControler();
        $this->detectAction();
        $this->detectBrand();
        $this->detectModel();
        
        $this->insertModule('activeModule', $this->getControler());
        
        $this->insertModule('Header', 'Header');
        $this->insertModule('LeftMenu', 'LeftMenu');        
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
    
    public function detectBrand()
    {
        if (isset($_GET['Brand']) && !empty($_GET['Brand'])) {
        	$this->_brand = ucfirst($_GET['Brand']);
        }
        
    }
    
    public function detectModel()
    {
        if (isset($_GET['']) && !empty($_GET['Brand'])) {
        	$this->_brand = ucfirst($_GET['Brand']);
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
    
    public function getBrand()
    {
        return $this->_brand;
    }
    
    public function getModel()
    {
        return $this->_model;
    }
    
}

$app = new Site_App();

$app->init();
$app->run();
$app->shutdown();
