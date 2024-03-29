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
 * @version    $Id: Edit.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class Catalog_Form_Fields_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Field
	 */
	private $_field;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_field = new KIT_Catalog_Field();

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

		$fieldType = new Zend_Form_Element_Select('fieldtype');
		$fieldType->setLabel('Field Type')
				  ->setRequired(true)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$fieldType->addMultiOptions(KIT_Catalog_Field::getFieldTypes());
		$this->addElement($fieldType);

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);

		if (!empty($id)) {
			$this->_field->loadById($id);
			$this->loadFromModel($this->_field);
			$submit->setLabel('Save');
			$this->setLegend($this->_field->getTitle() . ' - Field Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('Field Add Form');
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
				foreach ($this->_field->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$this->_field->setOptions($this->getValues());
				return $this->_field->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
