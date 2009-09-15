<?php
/**
 * User Site Main Page
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

ob_start();

require_once './config/bootstrap.inc';

if (defined('PTA_APP_DEBUG') && constant('PTA_APP_DEBUG')) {
	error_reporting(E_ALL);
	ini_set('dipslay_errors', 1);
} else {
	ini_set('dipslay_errors', 0);
}

class SiteApp extends PTA_App 
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
		if (empty($controller)) {
			$this->_controller = 'Categories';
			$this->insertModule('MostRecent', 'MostRecent');
		} else {
			$this->_controller = $controller;
		}
		$this->_action = $this->_router->getActiveAction();

		$this->insertModule('Header', 'Header');
		$this->insertModule('Categories', 'Categories');
		$this->insertModule('Catalog', 'Catalog');
		//$this->insertModule('activeModule', $this->getController());
		$this->insertModule($this->_controller, $this->_controller);
		$this->setActiveModule($this->_controller);

		if (($activeModule = $this->getActiveModule())) {
			$activeModule->setActive(true);
		} else {
			$this->redirect($this->getBaseUrl());
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

$app = new SiteApp();

$app->init();
$app->run();
$app->shutdown();

ob_end_flush();

