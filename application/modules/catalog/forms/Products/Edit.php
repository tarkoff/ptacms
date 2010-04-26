<?php
/**
 * Catalog Field Edit Form
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
 * @version    $Id: Edit.php 304 2010-04-19 19:07:18Z TPavuk $
 */

class Catalog_Form_Products_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Product
	 */
	private $_protuct;
	
	/**
	 * @var KIT_Catalog_Category
	 */
	private $_category;

	public function __construct($id = 0, $categoryId = null, $options = null)
	{
		$id = intval($id);
		$categoryId = intval($categoryId);

		$this->_protuct = KIT_Model_Abstract::get('KIT_Catalog_Product', $id);
		!empty($categoryId) || $categoryId = $this->_protuct->getCategoryId();
		$this->_category = KIT_Model_Abstract::get('KIT_Catalog_Category', $categoryId);
		if (empty($id) && !empty($categoryId)) {
			$this->_protuct->setCategoryId($categoryId);
		}

		parent::__construct($options);
		$this->setName('editForm');

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($title);

		$alias = new Zend_Form_Element_Text('alias');
		$alias->setLabel('Alias')
			  ->setRequired(true)
			  ->addFilter('StringtoLower')
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($alias);

		$brandsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Brand');
		$brandId = new Zend_Form_Element_Select('brandId');
		$brandId->setLabel('Brand')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim');
		$brandId->addMultiOptions(
			$brandsTable->getSelectedFields(
				array($brandsTable->getPrimary(), $brandsTable->getFieldByAlias('title')),
				null,
				true
			)
		);
		$this->addElement($brandId);

		$catsTable = $this->_category->getDbTable();
		$categoryId = new Zend_Form_Element_Select('categoryId');
		$categoryId->setLabel('Category')
				   ->setRequired(true)
				   ->addFilter('StripTags')
			 	  ->addFilter('StringTrim');
		$categoryId->addMultiOptions(
			$catsTable->getSelectedFields(
				array($catsTable->getPrimary(), $catsTable->getFieldByAlias('title')),
				null,
				true
			)
		);
		$categoryId->setValue($this->_category->getId());
		$this->addElement($categoryId);

		$prodCatsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Category');
		$categories = new Zend_Form_Element_Multiselect('categories');
		$categories->setLabel('Show in categories')
				   ->setRequired(false)
				   ->addFilter('StripTags')
			 	  ->addFilter('StringTrim');
		$categories->addMultiOptions(
			$catsTable->getSelectedFields(
				array($catsTable->getPrimary(), $catsTable->getFieldByAlias('title')),
				array($catsTable->getPrimary() . '<>' . (int)$this->_category->getId()),
				true
			)
		);
		if ($this->_protuct->getId()) {
			$categories->setValue(
				$prodCatsTable->getSelectedFields(
					array(
						$prodCatsTable->getPrimary(),
						$prodCatsTable->getFieldByAlias('categoryId')
					),
					array(
						$prodCatsTable->getFieldByAlias('productId') . '=' . $this->_protuct->getId(),
						$prodCatsTable->getFieldByAlias('isDefault') . ' <> 1'
					),
					true
				)
			);
		}
		$this->addElement($categories);

		$url = new Zend_Form_Element_Text('url');
		$url->setLabel('URL')
			->setRequired(false)
			->addFilter('StringTrim');
		$this->addElement($url);

		$driversUrl = new Zend_Form_Element_Text('driversUrl');
		$driversUrl->setLabel('Drivers URL')
				   ->setRequired(false)
				   ->addFilter('StringTrim');
		$this->addElement($driversUrl);

		$shortDescr = new Zend_Form_Element_Textarea('shortDescr');
		$shortDescr->setLabel('Description')
				   ->setRequired(false)
				   ->addFilter('StripTags')
				   ->addFilter('StringTrim');
		$this->addElement($shortDescr);

		$this->addDisplayGroup(
			array('title', 'alias', 'brandId', 'categoryId', 'categories', 'url', 'driversUrl', 'shortDescr'),
			'standard'
		);
		$group = $this->getDisplayGroup('standard');
		$group->setLegend('Standard');


// Dinamic fields section
		$categoryGroupsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category_Group');
		$groupsTable 		 = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group');
		$groupFieldsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');
		$fieldsTable         = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field');
		$productValuesTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Field_Value');
		$fieldValuesTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Value');
		
		$categoryGroupIdField = $categoryGroupsTable->getPrimary();
		$groupTitleField	  = $groupsTable->getFieldByAlias('title');
		$groupAliasField	  = $groupsTable->getFieldByAlias('alias');
		$fieldIdField         = $fieldsTable->getPrimary();
		$fieldAliasField	  = $fieldsTable->getFieldByAlias('alias');
		$fieldTitleField	  = $fieldsTable->getFieldByAlias('title');
		$fieldTypeField		  = $fieldsTable->getFieldByAlias('fieldType');
		$catGroupIdField	  = $groupFieldsTable->getFieldByAlias('categoryGroupId');
		$valueIdField         = $fieldValuesTable->getPrimary();
		$fieldValueField	  = $fieldValuesTable->getFieldByAlias('value');
		$valueFieldIdField	  = $fieldValuesTable->getFieldByAlias('fieldId');

		$catGroups = array();
		foreach ($categoryGroupsTable->getCategoryGroups($this->_category->getId()) as $group) {
			$catGroups[$group[$categoryGroupIdField]] = $group;
		}

		$selectElementsIds = array();
		foreach ($groupFieldsTable->getGroupFields(array_keys($catGroups)) as $field) {
			$element = KIT_Form_Element_Abstract::getElementByType(
				$field->$fieldTypeField,
				$field->$fieldAliasField,
				array(
					'label' => $field->$fieldTitleField
				)
			);

			empty($field->$fieldValueField) || $element->setValue($field->$fieldValueField);
			if (KIT_Form_Element_Abstract::TYPE_SELECT == $field->$fieldTypeField) {
				$selectElementsIds[$field->$fieldIdField] = $field->$fieldAliasField;
				$element->getView()->{$field->$fieldAliasField} = $field->$fieldIdField;
			}

			$this->addElement($element);

			if (isset($catGroups[$field[$catGroupIdField]])) {
				$groupRow = $catGroups[$field[$catGroupIdField]];
				if (($group = $this->getDisplayGroup($groupRow[$groupAliasField]))) {
					$group->addElement($element);
				} else {
					$this->addDisplayGroup(array($element->getName()), $groupRow[$groupAliasField]);
					$group = $this->getDisplayGroup($groupRow[$groupAliasField]);
					$group->setLegend($groupRow[$groupTitleField]);
				}
			}
		}

		foreach ($fieldValuesTable->getFieldValues(array_keys($selectElementsIds)) as $value) {
			if (isset($selectElementsIds[$value->$valueFieldIdField])) {
				$element = $this->getElement($selectElementsIds[$value->$valueFieldIdField]);
				$element->addMultiOption($value->$valueIdField, $value->$fieldValueField);
			}
		}
/*
		foreach ($this->_protuct->getCustomFields()->getFieldsValues() as $alias => $values) {
			if (($element = $this->getElement($alias))) {
				foreach ($values as $valueId => $value) {
						$element->setValue($valueId);
				}
			}
		}
*/
		//Set selected values for nultiselect
		$view = $this->getView();
		$view->selectValues = array();
		foreach ($productValuesTable->getProductValues($this->_protuct->getId()) as $fieldValue) {
			$view->selectValues[$fieldValue->$valueFieldIdField][$fieldValue->$valueIdField]
				= $fieldValue->$fieldValueField;
		}
		
		//Sort select options
		foreach ($selectElementsIds as $elementName) {
			$element = $this->getElement($elementName);
			$options = $element->getMultiOptions();
			asort($options);
			$element->setMultiOptions($options);
		}

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);

		if (!empty($id)) {
			//$this->_protuct->loadById($id);
			$this->loadFromModel($this->_protuct);
			$submit->setLabel('Save');
			$this->setLegend($this->_protuct->getTitle() . ' - Product Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend($this->_category->getTitle() . ' Category - Add Product Form');
		}
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			$auth = Zend_Auth::getInstance();
			if ($auth->hasIdentity() && $this->isValid($formData)) {
				$this->_protuct->setOptions($formData);
				$this->_protuct->setAuthorId($auth->getIdentity()->getId());
				if ($this->_protuct->save()) {
					if (($cats = $this->getElement('categories')->getValue())) {
						$prodCatsTable = KIT_Db_Table_Abstract::get(
							'KIT_Catalog_DbTable_Product_Category'
						);
						$prodCatsTable->setProductCategories(
							$this->_protuct->getId(),
							$cats
						);
					}
					return true;
				}
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
