<?php
/**
 * User Edit Form
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

class Default_Form_Users_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Default_User
	 */
	private $_user;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_user = new KIT_Default_User();
		$userGroupsTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_UserGroup');

		parent::__construct($options);
		$this->setName('user_editForm');

		$userId = new Zend_Form_Element_Hidden('id');
		$this->addElement($userId);

		$login = new Zend_Form_Element_Text('login');
		$login->setLabel('Login')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($login);

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password')
				 ->setRequired(false)
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		$this->addElement($password);

		$group = new Zend_Form_Element_Select('groupid');
		$group->setLabel('Group')
			   ->setRequired(true)
			   ->addFilter('StripTags')
			   ->addFilter('StringTrim');
		$group->addMultiOptions(
			$userGroupsTable->getSelectedFields(
				array(
					$userGroupsTable->getPrimary(),
					$userGroupsTable->getFieldByAlias('title')
				),
				null,
				true
			)
		);
		$this->addElement($group);

		$firstName = new Zend_Form_Element_Text('firstname');
		$firstName->setLabel('First Name')
				  ->setRequired(true)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$this->addElement($firstName);

		$lastName = new Zend_Form_Element_Text('lastname');
		$lastName->setLabel('Last Name')
				 ->setRequired(true)
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		$this->addElement($lastName);

		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('E-Mail')
			  ->setRequired(false)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($email);

		$status = new Zend_Form_Element_Select('status');
		$status->setLabel('Status')
			   ->setRequired(true)
			   ->addFilter('StripTags')
			   ->addFilter('StringTrim');
		$status->addMultiOptions(KIT_Default_User::getUserStatuses());
		$this->addElement($status);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'user_editForm_submit');

		if (!empty($id)) {
			$this->_user->loadById($id);
			$this->loadFromModel($this->_user);
			$submit->setLabel('Save');
			$this->setLegend($this->_user->getLogin() . 'User Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('User Add Form');
		}

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
				foreach ($this->_user->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}
			if ($this->isValid($formData)) {
				$data = (object)$this->getValues();
				$oldPassword = $this->_user->getPassword();
				$newPassword = KIT_Default_User::getEncodedPassword($data->password);
				$this->_user->setOptions($data);
				if ($oldPassword != $newPassword) {
					$this->_user->setPassword($newPassword);
				} else {
					$this->_user->setPassword($oldPassword);
				}
				return $this->_user->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
