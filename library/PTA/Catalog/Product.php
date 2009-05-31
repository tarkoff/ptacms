<?php
/**
 * Catalog Product
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product extends PTA_DB_Object 
{
	private $_title;
	private $_url;
	private $_categoryId;
	private $_brandId;
	private $_alias;
	private $_image;
	private $_shortDescr;
	private $_date;
	private $_customFields = array();
	private static $_customFieldsMeatData = array();

	public function __construct($prefix)
	{
		parent::__construct($prefix);
		$this->getTable()->setProduct($this);
	}

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($value)
	{
		$this->_categoryId = (int)$value;
		if ($this->getId()) {
			$this->_customFields = $this->buildCustomFields();
			$this->getTable()->setProduct($this);
		}
	}

	public static function getCustomFieldsMetaData($categoryId)
	{
		if (empty($categoryId)) {
			return null;
		}

		return PTA_DB_Table::get('Catalog_CategoryField')->getFieldsByCategory($categoryId, true, true);
	}

	public function buildCustomFields($fields = null)
	{
		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$valuesTable = PTA_DB_Table::get('Catalog_Value');

		if (empty($fields)) {
			$fields = (array)$categoryFieldTable->getFieldsByCategory($this->_categoryId, true, true);
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

		if ($this->getId()) {
			$fieldsValues = (array)$valuesTable->getValuesByProductId($this->getId());
		} else {
			$fieldsValues = array();
		}

		$fieldValueIdField = $valuesTable->getFieldByAlias('valueId');
		$fieldIdField = $valuesTable->getFieldByAlias('fieldId');
		$resultFields = array();
		foreach ($fieldsValues as $fieldId => $valeField) {
			$id = $valeField[$fieldIdField];
			if (isset($customFields[$id])) {
				$resultFields[$customFields[$id]] = $valeField[$fieldValueIdField];
			}
		}

		return $resultFields;
	}

	public function getCustomFields()
	{
		if (empty($this->_customFields)) {
			$this->_customFields = $this->buildCustomFields();
		}

		return $this->_customFields;
	}

	public function saveCustomFields($data)
	{
		if (!$this->getId()) {
			return false;
		}

//		$customFields = $this->getCustomFields();

		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$valuesTable = PTA_DB_Table::get('Catalog_Value');
		
		$fields = (array)self::getCustomFieldsMetaData($this->_categoryId);
		if (empty($fields)) {
			return false;
		}

		$fieldFieldId = $categoryFieldTable->getPrimary();
		$fieldAlias = PTA_DB_Table::get('Catalog_Field')->getFieldByAlias('alias');

		$valuesFieldId = $valuesTable->getFieldByAlias('fieldId');
		$valuesProductId = $valuesTable->getFieldByAlias('productId');
		$valuesValue = $valuesTable->getFieldByAlias('valueId');

		$valuesTable->getAdapter()->beginTransaction();
		$valuesTable->clearByFields(array('productId' => $this->getId()));
		foreach ($fields as $field) {
			$alias = $field[$fieldAlias];
			if (isset($data->$alias)) {
				$customFields[$alias] = $data->$alias;
				$resultData = array();
				$resultData[$valuesFieldId]= $field[$fieldFieldId];
				$resultData[$valuesProductId] = $this->getId();
				$resultData[$valuesValue] = $customFields[$alias];
			}

			try {
				$result = $valuesTable->insert($resultData);
			} catch (PTA_Exception $e) {
				echo $e->getMessage();
				//return false;
			}
		}
		$valuesTable->getAdapter()->commit();

		return $result;
	}

	function __get($customField)
	{
		$this->getCustomFields();
		if (!empty($this->_customFields)) {
			if (isset($this->_customFields[$customField])) {
				return $this->_customFields[$customField];
			}
			throw new PTA_Exception('Exception: ' . get_class($this) . "::{$customField} unknown property");
		}
	}
	
	function __set($customField, $value)
	{
		$this->getCustomFields();
		if (!empty($this->_customFields)) {
			if (isset($this->_customFields[$customField])) {
				$this->_customFields[$customField] = $value;
				return true;
			}
			throw new PTA_Exception('Exception: ' . get_class($this) . "::{$customField} unknown property");
		}
	}

	function __call($method, $args)
	{
		$this->getCustomFields();

		if (!empty($this->_customFields)) {
			foreach ($this->_customFields as $alias => $value) {
				$getMethod = "get{$alias}";
				$setMethod = "set{$alias}";
				if ($method == $getMethod) {
					return $value;
				} elseif ($method == $setMethod) {
					$this->_customFields[$alias] = $args;
					return true;
				}
			}
		}

		throw new PTA_Exception('Exception: ' . get_class($this) . "::{$method} unknown method called");
	}

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

	public function getAlias()
	{
		return $this->_alias;
	}

	public function setAlias($alias)
	{
		$this->_alias = $alias;
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

	public function getBrandId()
	{
		return $this->_brandId;
	}

	public function setBrandId($id)
	{
		$this->_brandId = (int)$id;
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
