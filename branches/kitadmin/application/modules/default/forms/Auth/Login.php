<?php
/**
 * User Authorization Form
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

class Default_Form_Auth_Login extends KIT_Form_Abstract
{
	/**
	 * @var Default_Model_User
	 */
	private $_user;

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->_user = new Default_Model_User();

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

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submit');
		$submit->setLabel('Login');
		$this->addElement($submit);
	}

	public function submit()
	{
		if (!$this->isPost()) {
			return false;
		}
		$formData = (array)$this->getPost();
		if ($this->isValid($formData)) {
			$data = (object)$this->getValues();
			$this->_user->setLogin($data->login);
			$this->_user->setPassword(
				Default_Model_User::getEncodedPassword($data->password)
			);
			return $this->_user->authenticate();
		} else {
			$this->populate($formData);
		}

		return false;
	}
}
