<?php

class PTA_Template extends PTA_Object {

    private $_file = null;
    private $_isIndex = false;
    
    public function __construct($prefix, $file)
    {
        $this->setPrefix($prefix);
        $this->_file = $file;
    }
    
    public function getFile()
    {
        return $this->_file;
    }
    
    public function isIndex()
    {
        return $this->_isIndex;
    }
    
    public function setIndex()
    {
        $this->_isIndex = true;
    }
    
    public function getTemplateFile()
    {
        return $this->_file;
    }
    
    public function getModule()
    {
        $this->getApp()->getModule($this->getPrefix());
    }
}
