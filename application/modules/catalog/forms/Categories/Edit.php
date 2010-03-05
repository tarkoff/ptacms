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
 * @version    $Id: Edit.php 278 2010-02-27 18:36:32Z TPavuk $
 */

class Catalog_Form_Categories_Edit extends KIT_Form_Abstract
{
	/**
	 * @var Catalog_Model_Category
	 */
	private $_category;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_category = new Catalog_Model_Category();

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
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($alias);

		$catsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category');
		$parentId = new Zend_Form_Element_Select('parentid');
		$parentId->setLabel('Parent')
				 ->setRequired(true)
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		$parentId->addMultiOptions(
			$catsTable->getParentSelectOptions(
				$catsTable->getPrimary(),
				$catsTable->getFieldByAlias('title')
			)
		);
		$this->addElement($title);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);
		
		if (!empty($id)) {
			$this->_category->loadById($id);
			$this->loadFromModel($this->_category);
			$submit->setLabel('Save');
		} else {
			$submit->setLabel('Add');
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
				foreach ($this->_category->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$data = (object)$this->getValues();
				$this->_category->setOptions($data);
				return $this->_category->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
