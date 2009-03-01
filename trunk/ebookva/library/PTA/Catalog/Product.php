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
    
    public function __construct($prefix)
    {
        parent::__construct($prefix);
    }
            
    public function getCategoryId()
    {
        return $this->_categoryId;
    }
    
    public function setCategoryId($value)
    {
        $this->_categoryId = (int)$value;
        if ($this->getId()) {
        	$this->customFeilds = $this->buildCustomFields();
        }        
    }
    
    public function buildCustomFields($fields = null)
    {
    	$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
    	$valuesTable = PTA_DB_Table::get('Catalog_Value');
    	
    	if (empty($fields)) {
        	$fields = (array)$valuesTable->getFieldsByCategory($this->_categoryId, true, true);
    	}
    	
    	if (empty($fields)) {
    		return array();
    	}

    	$fieldId = $categoryFieldTable->getPrimary();
		$alias = PTA_DB_Table::get('Catalog_Field')->getFieldByAlias('alias');
		
		$customFields = array();
    	foreach ($fields as $field) {
    		$customFields[$field[$fieldId]] = $field[$alias];
    	}
    	
    	$fieldsValues = (array)$valuesTable->getValuesByProductId($this->getId());
    	
    	$fieldIdField = $valuesTable->getFieldByAlias('fieldId');
    	$resultFields = array();
    	foreach ($customFields as $fieldId => $fieldAlias) {
    		$resultFields[$fieldAlias] = @$fieldsValues[$fieldIdField];
    	}
//var_dump($resultFields);
		return $resultFields;
    }
 /*   
	function __get($customField)
	{
		if (isset($this->_customFields[$customField])) {
			return $this->_customFields[$customField];
		}
		
		throw new PTA_Exception('Exception: ' . get_class($this) . "::{$customField} unknown property");
	}
	
	function __set($customField, $value)
	{
		if (isset($this->_customFields[$customField])) {
			$this->_customFields[$customField] = $value;
		}
		
		throw new PTA_Exception('Exception: ' . get_class($this) . "::{$customField} unknown property");
	}
	
	function __call($method, $args)
	{
		if (!empty($this->_customFields)) {
			foreach ($this->_customFields as $alias => $vale) {
				$getMethod = "get{$alias}";
				$setMethod = "set{$alias}";
				if ($method == $getMethod) {
					return $this->$getMethod();
				} elseif ($method == $setMethod) {
					return call_user_func_array(array($this, $setMethod), $args);
				}
			}
		}

		throw new PTA_Exception('Exception: ' . get_class($this) . "::{$method} unknown method called");
	}
*/

    public function loadById($id)
    {
    	parent::loadById($id);
    	
    	$this->_customFields = $this->buildCustomFields();
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
