<?php
/**
 * User Groups Edit Form
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

class Default_Form_Resources_Edit extends KIT_Form_Abstract
{
	/**
	 * @var Default_Model_Resource
	 */
	private $_resource;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_resource = new Default_Model_Resource();

		parent::__construct($options);
		$this->setName('resources_editForm');

//		$groupId = new Zend_Form_Element_Hidden('id');
		$module = new Zend_Form_Element_Text('module');
		$module->setLabel('Module')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');

		$controller = new Zend_Form_Element_Text('controller');
		$controller->setLabel('Controller')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');

		$action = new Zend_Form_Element_Text('action');
		$action->setLabel('Controller')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'user_editForm_submit');

		if (!empty($id)) {
			$this->_resource->loadById($id);
			//$groupId->setValue($this->_resource->getId());
			$module->setValue($this->_resource->getModule());
			$controller->setValue($this->_resource->getController());
			$action->setValue($this->_resource->getAction());
			$submit->setLabel('Save');
		} else {
			$submit->setLabel('Add');
		}

		$this->addElements(array($module, $controller, $action, $submit));
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
				foreach ($this->_resource->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$data = (object)$this->getValues();
				$this->_resource->setOptions($data);
				return $this->_resource->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
