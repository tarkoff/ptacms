<?php
/**
 * Catalog Category Edit Form
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
 * @version    $Id: Edit.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class Catalog_Form_Categories_Filter extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Category
	 */
	private $_category;
	/**
	 * @var Zend_Db_Select
	 */
	private $_select;
	private $_fields = array();
	
	/**
	 *
	 * @param KIT_Catalog_Category $category
	 * @param Zend_Db_Select $select
	 * @param array $options
	 * @return void
	 */
	public function __construct(KIT_Catalog_Category $category, Zend_Db_Select $select, $options = null)
	{
		$this->_category = $category;
		$this->_select   = $select;

		parent::__construct($options);
		$this->setName('filterForm');
		$this->setMethod('GET');
		$this->setLegend('Filter Form');
/*
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($title);

		$alias = new Zend_Form_Element_Text('alias');
		$alias->setLabel('Alias')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($alias);
*/

		$categoryGroupsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category_Group');
		$groupFieldsTable     = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');
		$fieldsTable          = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field');
		$fieldValuesTable     = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Value');
		$productCategoryTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Category');
		
		$categoryGroupIdField = $categoryGroupsTable->getPrimary();
		$fieldIdField         = $fieldsTable->getPrimary();
		$fieldAliasField	  = $fieldsTable->getFieldByAlias('alias');
		$fieldTitleField	  = $fieldsTable->getFieldByAlias('title');
		$valueIdField         = $fieldValuesTable->getPrimary();
		$fieldValueField	  = $fieldValuesTable->getFieldByAlias('value');
		$valueFieldIdField	  = $fieldValuesTable->getFieldByAlias('fieldId');
		

		foreach ($productCategoryTable->getCategoryFields($this->_category->getId(), true) as $field) {
			$this->_fields[$field->$fieldIdField]['alias'] = $field->$fieldAliasField;
			$this->_fields[$field->$fieldIdField]['title'] = $field->$fieldTitleField;
			$this->_fields[$field->$fieldIdField]['values'] = array(0 => 'Any');
		}
		
		foreach ($fieldValuesTable->getFieldValues(array_keys($this->_fields)) as $value) {
			if (isset($this->_fields[$value->$valueFieldIdField])) {
				$this->_fields[$value->$valueFieldIdField]['values'][$value->$valueIdField]
				 = $value->$fieldValueField;
			}
		}
//Zend_Registry::get('logger')->err($this->_fields);

		foreach ($this->_fields as $field) {
			$element = new Zend_Form_Element_Select($field['alias']);
			$element->setLabel($field['title'])
				 ->setRequired(false)
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
			asort($field['values']);
			$element->addMultiOptions($field['values']);
			$element->setValue(0);
			$this->addElement($element);
		}

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Filter');
		$this->addElement($submit);
	}

	public function submit()
	{
		$formData = $this->getValidValues($_GET);
//Zend_Registry::get('logger')->err($formData);
		if (!empty($formData)) {
			$this->applyFilter($formData);
		}

		$this->populate($formData);
		return true;
	}
	
	public function applyFilter($data = array())
	{
		!empty($data) || $data = $this->getValidValues($_GET);
		if (isset($data['submit'])) {
			unset($data['submit']);
		}

		$valuesIds = array();
		foreach ($this->_fields as $field) {
			if (!empty($data[$field['alias']])) {
				$valuesIds[] = (int)$data[$field['alias']];
			}
		}

		if (!empty($valuesIds)) {
			$productValuesTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Field_Value');
			$productsTable       = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');

			$productIdField = $productValuesTable->getFieldByAlias('productId');

			$select = $productValuesTable->select();
			$select->from($productValuesTable->getTableName(), array($productIdField));
			$select->where(
				$productValuesTable->getFieldByAlias('valueId') . ' IN (?)',
				array_unique($valuesIds)
			);

			$productIds = array();
			foreach ($productValuesTable->fetchAll($select) as $product) {
			$productIds[] = $product->$productIdField;
			}

			if (!empty($productIds)) {
				$this->_select->where(
					'prods.' . $productsTable->getPrimary() . ' IN (?)',
					array_unique($productIds)
				);
			} else {
				$this->_select->where(
					'prods.' . $productsTable->getPrimary() . ' IS NULL',
					array_unique($productIds)
				);
			}
		}
	}
}
