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
 * @version    $Id$
 */

class Catalog_Form_Products_Edit extends KIT_Form_Abstract
{
	/**
	 * @var Catalog_Model_Product
	 */
	private $_protuct;
	
	/**
	 * @var Catalog_Model_Category
	 */
	private $_category;

	public function __construct($id = 0, $categoryId = null, $options = null)
	{
		$id = intval($id);
		$categoryId = intval($categoryId);

		$this->_protuct  = KIT_Model_Abstract::get('Catalog_Model_Product', $id);
		!empty($categoryId) || $categoryId = $this->_protuct->getCategoryId();
		$this->_category = KIT_Model_Abstract::get('Catalog_Model_Category', $categoryId);

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

		$brandsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Brand');
		$brandId = new Zend_Form_Element_Select('brandId');
		$brandId->setLabel('Brand')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$brandId->addMultiOptions(
			$brandsTable->getSelectedFields(
				array(
					$brandsTable->getPrimary(),
					$brandsTable->getFieldByAlias('title')
				),
				null,
				true
			)
		);
		//$brandId->addMultiOption()
		$this->addElement($brandId);

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
			array('title', 'alias', 'brandId', 'url', 'driversUrl', 'shortDescr'),
			'standard'
		);
		$group = $this->getDisplayGroup('standard');
		$group->setLegend('Standard');


// Dinamic fields section
		$categoryGroupsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category_Group');
		$groupsTable 		 = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Group');
		$groupFieldsTable    = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Group_Field');
		$fieldsTable         = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field');
		$fieldValuesTable    = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Value');
		
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
		foreach ($categoryGroupsTable->getCategoryGroups($categoryId) as $group) {
			$catGroups[$group[$categoryGroupIdField]] = $group;
		}
//Zend_Registry::get('logger')->err($catGroups);

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
		
		foreach ($this->_protuct->getCustomFields()->getFieldsValuesIds() as $alias => $value) {
			if (($element = $this->getElement($alias))) {
				$element->setValue($value);
			}
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
			if ($this->isXmlHttpRequest()) {
				$newData = array();
				if (is_numeric($formData['id'])) {
					$newData['id'] = $formData['id'];
				} else {
					$newData['id'] = null;
				}
				foreach ($this->_protuct->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$data = (array)$this->getValues();
Zend_Registry::get('logger')->err(array('data'=>$data));
				$this->_protuct->setOptions($data);
				if (!$this->_protuct->getCategoryId()) {
					$this->_protuct->setCategoryId($this->_category->getId());
				}
				$auth = Zend_Auth::getInstance();
				if ($auth->hasIdentity()) {
					$this->_protuct->setAuthorId($auth->getIdentity()->getId());
					return $this->_protuct->save();
				}
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
