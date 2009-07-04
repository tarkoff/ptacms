<?php
/**
 * Admin Site Index Page
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

ob_start();

require_once realpath(dirname(__FILE__)) . '/config/bootstrap.inc';

if (defined('PTA_APP_DEBUG') && constant('PTA_APP_DEBUG')) {
	error_reporting(E_ALL);
	ini_set('dipslay_errors', 1);
} else {
	error_reporting(E_ALL);
	ini_set('dipslay_errors', 0);
}

class adminApp extends PTA_App 
{
	function __construct ()
	{
		parent::__construct('Gelezka', 'Index.tpl');

		$this->_router = Zend_Registry::get('router');

		$this->insertModules();
	}

	public function insertModules()
	{
		$controller = $this->_router->getActiveController();
		$this->_controller = empty($controller) ? 'Categories' : $controller;
		$this->_action = $this->_router->getActiveAction();

		if (!$this->loginByHash()) {
			$this->insertModule('activeModule', 'Authorizer');
		} else {
			$this->insertModule('Header', 'Header');
			$this->insertModule('activeModule', $this->getController());
		}
	}
	

	public function loginByHash()
	{
		//return true;
		$loginHash = $this->quote($this->getApp()->getCookie('SID'));
		if (empty($loginHash)) {
			return false;
		}

		$userByHash = PTA_DB_Table::get('User')->getUserByHash($loginHash);
		if ($userByHash instanceof PTA_User) {
			$this->getApp()->setUser($userByHash);
			return true;
		}

		return false;
		
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function getAction()
	{
		return $this->_action;
	}

	public function getQueryVar($key)
	{
		return $this->_router->getQueryVar($key);
	}

	public function getItem()
	{
		return $this->_router->getQueryVar('Item');
	}
}

$app = new adminApp();

$app->init();
$app->run();
$app->shutdown();
ob_end_flush();
