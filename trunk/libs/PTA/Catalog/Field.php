<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field extends PTA_DB_Object 
{
    private $_title;
    private $_url;
    private $_categoryId;
    private $_alias;
            
    public function getCategoryid()
    {
        return $this->_categoryId;
    }
    
    public function setCategoryid($value)
    {
        $this->_categoryId = (int)$value;
    }
    
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
    
    public function getAlias()
    {
        return $this->_alias;
    }
    
    public function setAlias($alais)
    {
        $this->_alias = $alais;
    }
    
}
