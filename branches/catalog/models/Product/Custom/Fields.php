<?php
/**
 * Catalog Product Category Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

class Catalog_Model_Product_Custom_Fields
{
	protected static $_fieldsMeta;
	protected $_fieldsValues = array();
	protected $_fieldsValuesIds = array();
	protected $_groupFieldsIds;
	protected $_categoryId;
	protected $_productId;

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($id)
	{
		$this->_categoryId = $id;
	}

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = $id;
	}
	
	public function getFieldsValues()
	{
		return $this->_fieldsValues;
	}

	public function getFieldsValuesIds()
	{
		return $this->_fieldsValuesIds;
	}

	public function build()
	{
		$categoryId = $this->getCategoryId();
		$productId  = $this->getProductId();
		if (empty($categoryId)) {
			return false;
		}
		

		$fieldsTable        = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field');
		$groupFieldsTable   = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Group_Field');
		$fieldValuesTable   = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Value');
		$productValuesTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product_Field_Value');

		$fieldAliasField   = $fieldsTable->getFieldByAlias('alias');
		$groupFieldPrimary = $groupFieldsTable->getPrimary();
		$groupFieldIdField = $productValuesTable->getFieldByAlias('fieldId');
		$fieldValueIdField = $productValuesTable->getFieldByAlias('valueId');
		$fieldValueField   = $fieldValuesTable->getFieldByAlias('value');
		
		$productValues = $this->_getProductValues();
		foreach ($this->_getCategoryFields() as $field) {
			$this->_fieldsValues[$field->$fieldAliasField] = 0;
			$this->_groupFieldsIds[$field->$fieldAliasField] = $field->$groupFieldPrimary;
			foreach ($productValues as $value) {
				if ($value->$groupFieldIdField == $field->$groupFieldPrimary) {
					if (empty($this->_fieldsValues[$field->$fieldAliasField])) {
						$this->_fieldsValues[$field->$fieldAliasField] = $value->$fieldValueField;
						$this->_fieldsValuesIds[$field->$fieldAliasField] = $value->$fieldValueIdField;
					} else {
						if (!is_array($this->_fieldsValues[$field->$fieldAliasField])) {
							$this->_fieldsValues[$field->$fieldAliasField]
								= (array)$this->_fieldsValues[$field->$fieldAliasField];
							$this->_fieldsValuesIds[$field->$fieldAliasField]
								= (array)$this->_fieldsValuesIds[$field->$fieldAliasField];
						}
						$this->_fieldsValues[$field->$fieldAliasField][] = $value->$fieldValueField;
						$this->_fieldsValuesIds[$field->$fieldAliasField][] = $value->$fieldValueIdField;
						
					}
				}
			}
		}
		
		//Zend_Registry::get('logger')->err(array('yyy'=>$productValues->toArray(), $this->_getCategoryFields()->toArray()));
	}
	
	protected function _getCategoryFields()
	{
		$categoryId = $this->getCategoryId();
		if (empty(self::$_fieldsMeta[$categoryId]['fields'])) {
			$productCategoryTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product_Category');
			self::$_fieldsMeta[$categoryId]['fields'] = $productCategoryTable->getCategoryFields($categoryId);
		}
		return self::$_fieldsMeta[$categoryId]['fields'];
	}

	protected function _getProductValues()
	{
		$productId = (int)$this->getProductId();
		$categoryId = (int)$this->getCategoryId();
		if (empty(self::$_fieldsMeta[$categoryId]['values'][$productId])) {
			$fieldValuesTable  = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product_Field_Value');
			self::$_fieldsMeta[$categoryId]['values'][$productId] = $fieldValuesTable->getProductValues($productId);
		}
		return self::$_fieldsMeta[$categoryId]['values'][$productId];
	}

	public function has($fieldAlias)
	{
		return isset($this->_fieldsValues[strtolower($fieldAlias)]);
	}

	public function __call($method, $args)
	{
		$method = strtolower($method);
		$alias  = str_replace(array('set', 'get'), '', $method);

		if ($this->has($alias)) {
			if (strcmp('get' . $alias, $method) === 0) {
				return $this->_fieldsValues[$alias];
			} else if (strcmp('set' . $alias, $method) === 0){
				$this->_fieldsValues[$alias] = current($args);
				return true;
			}
		}

		return false;
		throw new Zend_Exception('Exception: ' . get_class($this) . "::{$method} unknown method called");
	}

	/**
	 * Save data to database
	 *
	 * @return boolean
	 */
	public function save()
	{
		$productValuesTable  = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product_Field_Value');

		$sql = 'INSERT INTO ' . $productValuesTable->getTableName()
			   . ' ('
			    . $productValuesTable->getFieldByAlias('productId') . ', '
			    . $productValuesTable->getFieldByAlias('fieldId') . ', '
			    . $productValuesTable->getFieldByAlias('valueId')
			   . ') VALUES ';

		$data = array();
		$productId = $this->getProductId();
		$productValuesTable->clearProductValues($this->getProductId());
		foreach ($this->_fieldsValues as $fieldsAlias => $fieldValue) {
			foreach ((array)$fieldValue as $value) {
				if (!empty($value)) {
					$data[] = '(' .  $productId . ', '
							  . $this->_groupFieldsIds[$fieldsAlias] . ', '
							  . intval($value) . ')';
				}
			}
		}

		if (empty($data)) {
			return true;
		}

		return $productValuesTable->getAdapter()->query($sql . implode(', ', $data));
	}
}
