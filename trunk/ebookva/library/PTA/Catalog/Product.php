<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
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
    
    private $_categoryFieldTable;
    private $_valuesTable;
    
    public function __construct($prefix)
    {
        parent::__construct($prefix);
        
        $this->_categoryFieldTable = new PTA_Catalog_CategoryField_Table();
        $this->_valuesTable = new PTA_Catalog_Value_Table();
    }
            
    public function getCategoryId()
    {
        return $this->_categoryId;
    }
    
    public function setCategoryId($value)
    {
        $this->_categoryId = (int)$value;
        if ($this->getId()) {
        	$this->_buildCustomFields();
        }        
    }
    
    private function _buildCustomFields()
    {
        $fields = (array)$this->_categoryFieldTable->getFieldsByCategory($this->_categoryId, true, true);
    	
    	if (empty($fields)) {
    		return;
    	}

    	$fieldId = $this->_categoryFieldTable->getPrimary();
		$alias = PTA_DB_Table::get('Catalog_Field')->getFieldByAlias('alias');
		
		$customFields = array();
    	foreach ($fields as $field) {
    		$customFields[$field[$fieldId]] = $field[$alias];
    	}
    	
    	$fieldsValues = (array)$this->_valuesTable->getValuesByProductId($this->getId());
    	
    	$fieldIdField = $this->_valuesTable->getFieldByAlias('fieldId');
    	foreach ($customFields as $fieldId => $fieldAlias) {
    		$this->_customFields[$fieldAlias] = @$fieldsValues[$fieldIdField];
    	}
var_dump($this->_customFields);
    }

    public function loadById($id)
    {
    	parent::loadById($id);
    	
    	$this->_buildCustomFields();
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
