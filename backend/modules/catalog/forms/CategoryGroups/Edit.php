<?php
/**
 * Catalog Category Fields Form
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
 * @version    $Id: FieldGroups.php 286 2010-03-18 23:22:45Z TPavuk $
 */

class Catalog_Form_CategoryGroups_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Category_Group
	 */
	private $_model;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_model = KIT_Model_Abstract::get('KIT_Catalog_Category_Group', $id);

		parent::__construct($options);
		$this->setName('fieldsForm');
		$this->setLegend('Category Groups Edit Form');

		$catsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
		$groupsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group');
		$catGroupsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category_Group');

		$categoryId = new Zend_Form_Element_Select('categoryId');
		$categoryId->setLabel('Category')
				   ->setRequired(true)
				   ->addFilter('StripTags')
				   ->addFilter('StringTrim');
		$categoryId->addMultiOptions(
			$catsTable->getSelectedFields(
				array(
					$catsTable->getPrimary(),
					$catsTable->getFieldByAlias('title')
				),
				null,
				true
			)
		);
		$this->addElement($categoryId);

		$groupId = new Zend_Form_Element_Select('groupId');
		$groupId->setLabel('Field Group')->setRequired(true);
		$groupId->addMultiOptions(
			$groupsTable->getSelectedFields(
				array(
					$groupsTable->getPrimary(),
					$groupsTable->getFieldByAlias('title')
				),
				null,
				true
			)
		);
		$this->addElement($groupId);

		$sortOrder = new Zend_Form_Element_Text('sortOrder');
		$sortOrder->setLabel('Order')
				  ->setRequired(true)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$this->addElement($sortOrder);

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);

		if (!empty($id)) {
			$this->_model->loadById($id);
			$this->loadFromModel($this->_model);
			$submit->setLabel('Save');
			$this->setLegend('Field Group Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('Field Group Add Form');
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
				if (isset($formData['CATEGORYGROUPS_CATEGORY'])) {
					$newData['categoryId'] = (int)$formData['CATEGORYGROUPS_CATEGORY'];
				}
				if (isset($formData['CATEGORYGROUPS_GROUP'])) {
					$newData['groupId'] = (int)$formData['CATEGORYGROUPS_GROUP'];
				}
				if (isset($formData['CATEGORYGROUPS_SORTORDER'])) {
					$newData['sortOrder'] = (int)$formData['CATEGORYGROUPS_SORTORDER'];
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$this->_model->setOptions($this->getValues());
				return $this->_model->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
