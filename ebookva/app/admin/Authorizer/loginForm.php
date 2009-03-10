<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: editForm.php 13 2009-02-28 14:47:29Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Authorizer_LoginForm extends PTA_Control_Form 
{
	public function __construct($prefix)
	{
		parent::__construct($prefix);
		$this->setTitle('Login Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('login', 'Login', true, '');
		$title->setSortOrder(100);
		$title->setCssClass('textField');
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Text('password', 'Password', false, '');
		$alias->setSortOrder(200);
		$alias->setCssClass('textField');
		$this->addVisual($alias);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
		}

	public function onLoad()
	{
		$data = new stdClass();
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

		$userTable = PTA_DB_Table::get('User');
		$dbUser = $userTable->findByFields(array('login'=>$data->login));
		if (!empty($dbUser)) {
			$passwdField = $userTable->getFieldByAlias('passwd');
			if ($dbUser[$passwdField] == PTA_User::getPasswdHash($data->passwd)) {
				$user = new PTA_User('currentUser');
				$user->loadFrom($dbUser);
				$this->getApp()->setUser($user);
				$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
			}
		} else {
			$this->redirect($_SERVER['PHP_SELF']);
		}

		if ($this->_field->save() || $this->_copy) {
			$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}

		return true;
	}
}
