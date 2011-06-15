<?php
/**
 * Users Authorize Controler
 *
 * @package PTA_Core
 * @copyright 2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Authorizer extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Authorizer.tpl');
	}
	
	public function init()
	{
		$action = $this->getApp()->getAction();
		
		switch (ucfirst($action)) {
			case 'Logout':
				$this->logOut();
			break;
			default:
				$this->login();
		}
		
	}

	public function login()
	{
		parent::init();
/*
		if ($this->loginByHash()) {
			return true;
		}
*/
		return $this->loginByPassword();
	}
	
	public function loginByHash()
	{
		$app = $this->getApp();
		$loginHash = $this->quote($app->getCookie('login'));
		if (empty($loginHash)) {
			return false;
		}

		$userByHash = PTA_DB_Table::get('User')->getUserByHash($loginHash);
		if ($userByHash instanceof PTA_User) {
			$app->setUser($userByHash);
			return true;
		}

		return false;
		
	}
	
	public function loginByPassword()
	{
		include_once './Authorizer/loginForm.php';
		$loginForm = new Authorizer_LoginForm('loginForm');
		$this->addVisual($loginForm);
	}

	public function logOut()
	{
		$app = $this->getApp();
		$app->setCookie('SID', '', -100);
		$this->redirect($app->getBaseUrl());
	}
}
