<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Users_editForm extends PTA_Control_Form 
{
	private $_user;
	private $_copy;

	public function __construct($prefix, $user, $copy = false)
	{
		$this->_user = $user;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('User Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('login', 'User Login', true, '');
		$title->setSortOrder(100);
		$title->setCssClass('textField');
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Password('password', 'User Password', true, '');
		$alias->setSortOrder(200);
		$alias->setCssClass('textField');
		$this->addVisual($alias);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_user->loadTo($data);
		$data->submit = 'save';

		return $data;
	}

	public function onSubmit(&$data)
	{
		$invalidFields = $this->validate($data);
		if (!empty($invalidFields)) {
			foreach ($invalidFields as $field) {
				echo 'Filed ' . $field->getLabel() . ' is required!<br />';
			}

			return false;
		}

		$this->_user->loadFrom($data);

		if ($this->_copy) {
			$this->_user->setId(null);
		}

		$this->_user->setPassword(PTA_User::getPasswordHash($data->password));

		if ($this->_user->save() || $this->_copy) {
			$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}

		return true;
	}
}
