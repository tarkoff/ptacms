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

class Default_Form_Usergroups_Edit extends KIT_Form_Abstract
{
	/**
	 * @var Default_Model_UserGroup
	 */
	private $_userGroup;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_userGroup = new Default_Model_UserGroup();

		parent::__construct($options);
		$this->setName('userGroup_editForm');

		$groupId = new Zend_Form_Element_Hidden('id');
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'user_editForm_submit');

		if (!empty($id)) {
			$this->_userGroup->loadById($id);
			$groupId->setValue($this->_userGroup->getId());
			$title->setValue($this->_userGroup->getTitle());
			$submit->setLabel('Save');
			$this->setLegend($this->_userGroup->getTitle() . ' - User Group Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('User Group Add Form');
		}

		$this->addElements(array($groupId, $title, $submit));
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
				if (isset($formData['USERGROUPS_TITLE'])) {
					$newData['title'] = $formData['USERGROUPS_TITLE'];
				} else {
					return false;
				}
				$formData = $newData;
			}

			if ($this->isValid($formData)) {
				$this->_userGroup->setTitle($this->getValue('title'));

				return $this->_userGroup->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
