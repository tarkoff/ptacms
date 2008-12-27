<?php

class Main extends PTA_WebModule
{
    private $_leftMenu;
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Main.tpl');
        
        $this->_leftMenu = new UDL_Menu();
        var_dump('ddddddddddddddddddd', $this->_leftMenu->getLeftMenu());
        
    }
    
    public function init()
    {
        parent::init();
        
    }
        
}
