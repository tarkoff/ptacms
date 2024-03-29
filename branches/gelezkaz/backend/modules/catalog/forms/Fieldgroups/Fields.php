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
 * @version    $Id: Fields.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class Catalog_Form_FieldGroups_Fields extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Category_Group
	 */
	private $_model;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_model = KIT_Model_Abstract::get('KIT_Catalog_Category_Group', $id);

		$catFieldsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');
		$fieldsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field');
		$fieldIdField = $fieldsTable->getPrimary();
		$fieldTitleField = $fieldsTable->getFieldByAlias('title');
		
		parent::__construct($options);
		$this->setName('fieldsForm');
		$this->setLegend('Field Group Fields');

		$deniedOptions = array();
		foreach ($catFieldsTable->getFreeFields($id) as $field) {
			$deniedOptions[$field[$fieldIdField]] = $field[$fieldTitleField];
		}
		$deniedRes = new Zend_Form_Element_Select('freeFields');
		$deniedRes->setLabel('Free Group Fields')
				  ->setRequired(false)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$deniedRes->addMultiOptions($deniedOptions);

		$this->addElement($deniedRes);

		$allowedOptions = array();
		foreach ($catFieldsTable->getGroupFields($id) as $field) {
			$allowedOptions[$field[$fieldIdField]] = $field[$fieldTitleField];
		}
		$allowedRes = new Zend_Form_Element_Select('groupFields');
		$allowedRes->setLabel('Field Group Fields')->setRequired(false);
		$allowedRes->addMultiOptions($allowedOptions);
		$this->addElement($allowedRes);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save');
		$this->addElement($submit);
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
				$catFieldsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');
				if ($catFieldsTable->setGroupFields(
					$this->_model->getGroupId(),
					$formData['groupFields']
				)
			) {
				return true;
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
