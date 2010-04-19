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

class Default_Form_Menus_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Default_Menu
	 */
	private $_menu;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_menu = new KIT_Default_Menu();

		parent::__construct($options);
		$this->setName('editForm');

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');

		$alias = new Zend_Form_Element_Text('alias');
		$alias->setLabel('Alias')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');

		$menusTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Menu');
		$parentId = new Zend_Form_Element_Select('parentid');
		$parentId->setLabel('Parent')
				 ->setRequired(true)
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		$parentId->addMultiOptions($menusTable->getMenusOptions());

		$resourcesTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Resource');
		$resourceId = new Zend_Form_Element_Select('resourceid');
		$resourceId->setLabel('Resource')
				   ->setRequired(true)
				   ->addFilter('StripTags')
				   ->addFilter('StringTrim');
		$resourceId->addMultiOptions($resourcesTable->getResourcesOptions());

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submit');

		if (!empty($id)) {
			$this->_menu->loadById($id);
			$title->setValue($this->_menu->getTitle());
			$alias->setValue($this->_menu->getAlias());
			$parentId->setValue($this->_menu->getParentId());
			$resourceId->setValue($this->_menu->getResourceId());
			$submit->setLabel('Save');
			$this->setLegend($this->_menu->getTitle() . ' - Menu Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('Menu Add Form');
		}

		$this->addElements(array($title, $alias, $parentId, $resourceId, $submit));
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
				foreach ($this->_menu->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$data = (object)$this->getValues();
				$oldParentId = $this->_menu->getParentId();
				$this->_menu->setOptions($data);
				if ($this->_menu->getId() && $this->_menu->getParentId() != $oldParentId) {
					return $this->_menu->moveTo();
				} else {
					return $this->_menu->save();
				}
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
