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

class Catalog_Form_CategoryGroups_Fields extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Field_Group_Field
	 */
	private $_model;

	public function __construct($cgid = 0, $options = null)
	{
		$cgid = intval($cgid);

		$this->_model = KIT_Model_Abstract::get('KIT_Catalog_Field_Group_Field');
		$this->_model->setCategoryGroupId($cgid);

		$catFieldsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');
		$fieldsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field');

		$fieldIdField    = $fieldsTable->getPrimary();
		$fieldTitleField = $fieldsTable->getFieldByAlias('title');

		parent::__construct($options);
		$this->setName('fieldsForm');
		$this->setLegend('Group Fields');

		$deniedOptions = array();
		foreach ($catFieldsTable->getFreeFields($cgid) as $field) {
			$deniedOptions[$field[$fieldIdField]] = $field[$fieldTitleField];
		}
		$deniedRes = new Zend_Form_Element_Select('fieldId');
		$deniedRes->setLabel('Field')
				  ->setRequired(false)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$deniedRes->addMultiOptions($deniedOptions);
		$this->addElement($deniedRes);

		$sortOrder = new Zend_Form_Element_Text('sortOrder');
		$sortOrder->setLabel('Order')
				  ->setRequired(true)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$this->addElement($sortOrder);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save');
		$this->addElement($submit);
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
				foreach ($this->_model->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValidPartial($formData)) {
				$this->_model->setFieldId($formData['fieldid']);
				$this->_model->setSortOrder($formData['sortorder']);
				return $this->_model->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
