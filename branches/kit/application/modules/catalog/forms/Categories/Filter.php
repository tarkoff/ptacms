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

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_category = new KIT_Catalog_Category();

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

		$catsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
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
		$this->addElement($parentId);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);
		
		if (!empty($id)) {
			$this->_category->loadById($id);
			$this->loadFromModel($this->_category);
			$submit->setLabel('Save');
			$this->setLegend($this->_category->getTitle() . ' - Category Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('Category Add Form');
		}
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			if ($this->isValid($formData)) {
				$this->_category->setOptions($this->getValues());
				return $this->_category->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
