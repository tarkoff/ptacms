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
 * @version    $Id: Fields.php 466 2011-03-25 15:59:22Z TPavuk $
 */

class KIT_Catalog_Product_Custom_Fields
{
	protected static $_fieldsMeta;
	protected $_fieldsValues = array();
	protected $_groupFields = array();
	protected $_groupFieldsIds = array();
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

	public function build()
	{
		$categoryId = $this->getCategoryId();
		$productId  = $this->getProductId();
		if (empty($categoryId)) {
			return false;
		}

		$groupsTable         = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group');
		$fieldsTable         = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field');
		$groupFieldsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');
		$fieldValuesTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Value');
		$productValuesTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Field_Value');
		$categoryGroupsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category_Group');

		$groupTitleField   = $groupsTable->getFieldByAlias('title');
		$groupAliasField   = $groupsTable->getFieldByAlias('alias');
		$fieldIdField      = $fieldsTable->getPrimary();
		$fieldAliasField   = $fieldsTable->getFieldByAlias('alias');
		$fieldTitleField   = $fieldsTable->getFieldByAlias('title');
		$groupFieldPrimary = $groupFieldsTable->getPrimary();
		$groupFieldIdField = $productValuesTable->getFieldByAlias('fieldId');
		$fieldValueIdField = $productValuesTable->getFieldByAlias('valueId');
		$fieldValueField   = $fieldValuesTable->getFieldByAlias('value');
		$catGroupIdField   = $categoryGroupsTable->getPrimary();

		foreach ($categoryGroupsTable->getCategoryGroups($this->getCategoryId()) as $group) {
			$this->_groupFields[$group->$catGroupIdField]['alias'] = $group->$groupAliasField;
			$this->_groupFields[$group->$catGroupIdField]['title'] = $group->$groupTitleField;
			$this->_groupFields[$group->$catGroupIdField]['fields'] = array();
		}
		$productValues = $this->_getProductValues();
		foreach ($this->_getCategoryFields() as $field) {
			$fieldAlias = strtolower($field->$fieldAliasField);
			$this->_fieldsValues[$fieldAlias] = array();
			$this->_groupFieldsIds[$fieldAlias] = $field->$groupFieldPrimary;
			$feieldData = array();
			$feieldData['title'] = $field->$fieldTitleField;
			$feieldData['alias'] = $field->$fieldAliasField;

			foreach ($productValues as $value) {
				if ($value->$groupFieldIdField == $field->$groupFieldPrimary) {
					$this->_fieldsValues[$fieldAlias][$value->$fieldValueIdField] = $value->$fieldValueField;
				}
			}
			$feieldData['values'] = $this->_fieldsValues[$fieldAlias];
			$this->_groupFields[$field->$catGroupIdField]['fields'][$field->$fieldIdField] = $feieldData;
		}
	}

	protected function _getCategoryFields()
	{
		$categoryId = $this->getCategoryId();
		if (empty(self::$_fieldsMeta[$categoryId]['fields'])) {
			$productCategoryTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Category');
			self::$_fieldsMeta[$categoryId]['fields'] = $productCategoryTable->getCategoryFields($categoryId);
		}
		return self::$_fieldsMeta[$categoryId]['fields'];
	}

	protected function _getProductValues()
	{
		$productId = (int)$this->getProductId();
		$categoryId = (int)$this->getCategoryId();
		if (empty(self::$_fieldsMeta[$categoryId]['values'][$productId])) {
			$fieldValuesTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Field_Value');
			self::$_fieldsMeta[$categoryId]['values'][$productId] = $fieldValuesTable->getProductValues($productId);
		}
		return self::$_fieldsMeta[$categoryId]['values'][$productId];
	}

	/**
	 * Check if product has field by alias
	 *
	 * @param string $fieldAlias
	 * @return boolean
	 */
	public function has($fieldAlias)
	{
		return isset($this->_fieldsValues[strtolower($fieldAlias)]);
	}

	/**
	 * Set to null all values
	 *
	 * @return void
	 */
	public function dropValues()
	{
		foreach ($this->_fieldsValues as $fieldsAlias => $fieldValues) {
			$this->_fieldsValues[$fieldsAlias] = array();
		}

	}

	public function getProductGroups()
	{
		return $this->_groupFields;
	}

	public function __call($method, $args)
	{
		$method = strtolower($method);
		$alias  = preg_replace('/(^set)|(^get).+/i', '', $method, 1);
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
		$productId = $this->getProductId();
		if (empty($productId)) {
			return false;
		}

		$productValuesTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Field_Value');

		$sql = 'INSERT INTO ' . $productValuesTable->getTableName()
			   . ' ('
			    . $productValuesTable->getFieldByAlias('productId') . ', '
			    . $productValuesTable->getFieldByAlias('fieldId') . ', '
			    . $productValuesTable->getFieldByAlias('valueId')
			   . ') VALUES ';

		$data = array();
		foreach ($this->_fieldsValues as $fieldsAlias => $fieldValues) {
			foreach ((array)$fieldValues as $value) {
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

		$productValuesTable->clearProductValues($productId);

		return $productValuesTable->getAdapter()->query($sql . implode(', ', $data));
	}
}
