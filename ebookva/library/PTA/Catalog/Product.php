<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Product.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product extends PTA_DB_Object 
{
    private $_title;
    private $_url;
    private $_categoryId;
    private $_manufacturerId;
    private $_image;
    private $_shortDescr;
    private $_date;
    private $_customFields = array();
    
    private $_fieldsTable;
    private $_valuesTable;
    
    public function __construct($prefix)
    {
        parent::__construct($prefix);
        
        $this->_fieldsTable = new PTA_Catalog_Field_Table();
        $this->_valuesTable = new PTA_Catalog_Value_Table();
    }
            
    public function getCategoryId()
    {
        return $this->_categoryId;
    }
    
    public function setCategoryId($value)
    {
        $this->_categoryId = (int)$value;
        
        $this->_customFields = $this->_fieldsTable->getFieldsByCategoryId((int)$value);
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
    
    public function getImage()
    {
        return $this->_image;
    }
    
    public function setImage($image)
    {
        $this->_image = $image;
    }
    
    public function getShortDescr()
    {
        return $this->_shortDescr;
    }
    
    public function setShortDescr($descr)
    {
        $this->_shortDescr = $descr;
    }
    
    public function getManufacturerId()
    {
        return $this->_shortDescr;
    }
    
    public function setManufacturerId($id)
    {
        $this->_manufacturerId = (int)$id;
    }
    
    public function getDate()
    {
        return $this->_date;
    }
    
    public function setDate($date)
    {
        $this->_date = $date;
    }
}
