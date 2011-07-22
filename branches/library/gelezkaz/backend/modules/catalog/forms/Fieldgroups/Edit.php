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

class Catalog_Form_FieldGroups_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Field_Group
	 */
	private $_model;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_model = KIT_Model_Abstract::get('KIT_Catalog_Field_Group', $id);

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

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);

		if (!empty($id)) {
			$this->loadFromModel($this->_model);
			$submit->setLabel('Save');
			$this->setLegend($this->_model->getTitle() . ' - Field Group Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('Field Group Edit Form');
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
				foreach ($this->_model->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
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
