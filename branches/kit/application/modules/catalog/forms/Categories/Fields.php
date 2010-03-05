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
 * @version    $Id: Acl.php 273 2010-02-17 12:42:59Z TPavuk $
 */

class Catalog_Form_Categories_Fields extends KIT_Form_Abstract
{
	/**
	 * @var Catalog_Model_Category
	 */
	private $_model;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_model = KIT_Model_Abstract::get('Catalog_Model_Category', $id);

		$catFieldsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category_Field');

		parent::__construct($options);
		$this->setName('fieldsForm');

		$deniedOptions = array();
		foreach ($catFieldsTable->getFreeFields($id) as $field) {
			$deniedOptions[$field['FIELDS_ID']] = $field['FIELDS_TITLE'];
		}
		$deniedRes = new Zend_Form_Element_Select('freeFields');
		$deniedRes->setLabel('Free Fields')
				  ->setRequired(false)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$deniedRes->addMultiOptions($deniedOptions);

		$this->addElement($deniedRes);

		$allowedOptions = array();
		foreach ($catFieldsTable->getCategoryFields($id) as $field) {
			$allowedOptions[$field['FIELDS_ID']] = $field['FIELDS_TITLE'];
		}
		$allowedRes = new Zend_Form_Element_Select('categoryFields');
		$allowedRes->setLabel('Category Fields')->setRequired(false);
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
				$catFieldsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category_Field');
				if ($catFieldsTable->setCategoryFields(
					$this->_model->getId(),
					$formData['categoryFields']
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
