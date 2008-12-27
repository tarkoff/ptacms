<?php

class MenuBuilder extends PTA_WebModule
{
    private $_menu;
    
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'menuBuilder.tpl');
        
        $this->_menu = new UDL_Menu();
    }
    
    public function init()
    {
        parent::init();
        
        $action = $this->getApp()->getAction();
        $model = $this->getApp()->getModel();
        
        switch ($action) {
            case 'Add': 
                    $this->addAction();
            break;
            
            case 'List':
                    $this->listAction();
            break;
            
            case 'Edit':
                    $this->editAction($model);
            break;
        }
    }
    
    public function editAction($id=null)
    {
        $this->setVar('tplMode', 'edit');
        
    }
    
    public function listAction()
    {
        $this->setVar('tplMode', 'list');
    }
        
}
