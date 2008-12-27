<?php
class PTA_Catalog_Manufacturer extends PTA_DB_Object 
{
    private $_title;
    private $_url;
        
    public function getUrl()
    {
        return $this->_url;
    }
    
    public function setUrl($value)
    {
        $this->_url = $value;
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
    }
        
}
