<?php

class Header extends PTA_WebModule
{
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Header.tpl');
        
        $this->getApp()->insertModule('MainMenu', 'MainMenu');
    }
    
    public function init()
    {
        
    }
    
}
