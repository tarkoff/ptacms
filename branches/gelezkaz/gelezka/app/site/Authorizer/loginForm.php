<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: loginForm.php 62 2009-05-31 16:59:23Z TPavuk $
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

		$alias = new PTA_Control_Form_Password('password', 'Password', false, '');
		$alias->setSortOrder(200);
		$alias->setCssClass('textField');
		$this->addVisual($alias);

		$submit = new PTA_Control_Form_Submit('submit', 'Login', true, 'Login');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
		}

	public function onLoad()
	{
		$data = new stdClass();
		$data->password = '';
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
		$dbUser = $userTable->findByFields(
								array('login', 'password'),
								array($this->quote($data->login), PTA_User::getPasswordHash($data->password)),
								true
							);

		if (!empty($dbUser)) {
			$this->getApp()->setUser(current($dbUser));
			$url = $this->getApp()->getActiveModule()->getModuleUrl();
			$this->redirect(empty($url) ? PTA_ADMIN_URL : $url);
		}

		return true;
	}
}
