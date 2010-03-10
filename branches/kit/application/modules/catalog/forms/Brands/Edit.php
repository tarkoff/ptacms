<?php
/**
 * Catalog Brands Edit Form
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

class Catalog_Form_Brands_Edit extends KIT_Form_Abstract
{
	/**
	 * @var Catalog_Model_Brand
	 */
	private $_brand;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_brand = new Catalog_Model_Brand();

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

		$url = new Zend_Form_Element_Text('url');
		$url->setLabel('Url')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim');
		$this->addElement($url);

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);
		
		if (!empty($id)) {
			$this->loadFromModel($this->_brand);
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
				foreach ($this->_brand->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$data = (object)$this->getValues();
				$this->_brand->setOptions($data);
				return $this->_brand->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
