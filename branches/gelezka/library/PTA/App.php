<?php
/**
 *  PTA App Module
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_App extends PTA_WebModule 
{
	protected $_templateEngine;
	protected $_modules = array();
	protected $_user;
	protected $_messages = array();

	private $_appStartTime;
	private $_initStartTime;
	private $_runStartTime;
	private $_shutdownStartTime;

	protected $_controller;
	protected $_action;
	protected $_router;
	protected $_ajaxMode = false;

	private static $_instance;

	public function __construct($prefix, $tpl=null)
	{
		$this->_appStartTime = self::getmicrotime();

		$this->setPrefix($prefix);
//		$this->setRequestVars();

		$this->_templateEngine = PTA_TemplateEngine::getInstance();

		if(!empty($tpl)){
			$this->getTemplateEngine()->registerTemplate($this->getPrefix(), $tpl);
			$this->setVar('tpl', $tpl);
		}

		self::$_instance = $this;
	}

	public static function getmicrotime() 
	{ 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float)$usec + (float)$sec); 
	}

	public function setRequestVars()
	{
		$urlParams = @parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		
		if (empty($urlParams)) {
			return true;
		}

		$matches = array();
		preg_match_all("/(([a-z0-9_-]+)=([a-z0-9_-]+))+/", $urlParams, $matches);

		if (!empty($matches[2])) {
			for ($index = 0; $index < count($matches[2]); $index++) {
				$_REQUEST[$matches[2][$index]] = $matches[3][$index];
			}
		}
	}

	/**
	 * Get App Keywords
	 *
	 * @return array
	 */
	public function getKeywords()
	{
		return (array)$this->getVar('keywords');
	}
	
	/**
	 * Add App keyword
	 *
	 * @param string $keyword
	 */
	public function addKeyword($keyword)
	{
		$keywords = $this->getKeywords();
		$keywords[] = trim($keyword);
		$this->setVar('keywords', $keywords);
	}

	/**
	 * Set App Title
	 *
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->setVar('title', trim($title));
		$this->setDescription($title);
	}

	/**
	 * Set meta tag description
	 *
	 * @param string $descr
	 */
	public function setDescription($descr)
	{
		$this->setVar('descr', trim($descr));
	}

	public static function getInstance()
	{
		if (empty(self::$_instance)) {
			self::$_instance = new self('app');
		}

		return self::$_instance;
	}

	/**
	 * Set Current App User
	 *
	 * @param PTA_User $user
	 */
	public function setUser(PTA_User $user)
	{
		if ($this->setCookie('SID', $user->getSessionHash())) {
			$user->saveUserSession();
			$this->_user = $user;
		}
	}

	/**
	 * Return TRUE If App Work In Ajax Mode
	 *
	 * @return boolean
	 */
	public function ajaxMode()
	{
		return $this->_ajaxMode;
	}

	/**
	 * Activate Ajax Work Mode
	 *
	 * @param boolean $mode
	 */
	public function setAjaxMode($mode)
	{
		$this->_ajaxMode = (boolean)$mode;
	}

	/**
	 * Return current user
	 *
	 * @return PTA_User
	 */
	public function getUser()
	{
		return $this->_user;
	}

	public function initModules($modules = null)
	{
		if(empty($modules)) {
			return false;
		}

		foreach ($modules as $module) {
			if (!$module->inited()) {
				$module->init();
			}
		}

		return true;
	}

	public function init()
	{
		$this->_initStartTime = self::getmicrotime();

		parent::init();

		$this->_templateEngine->init();
		$this->initModules($this->getModules());

		$this->_ajaxMode = ($this->getHttpVar($this->getPrefix() . '_ajaxMode') ? true : false);

		$this->setVar('appInitTime', number_format((self::getmicrotime() - $this->_initStartTime), 4, '.', ''));
	}

	public function run()
	{
		$this->_runStartTime = self::getmicrotime();

		parent::run();

		$this->_templateEngine->run();
		$this->runModules($this->getModules());
		
		$this->setVar('appRunTime', number_format((self::getmicrotime() - $this->_runStartTime), 4, '.', ''));
	}

	public function runModules($modules)
	{
		if (empty($modules)) {
			return false;
		}

		foreach ($modules as $module) {
			if (!$module->runned()) {
				$module->run();
			}
		}

		return true;
	}

	public function shutdown()
	{
		$this->_shutdownStartTime = self::getmicrotime();

		parent::shutdown();

		if (empty($this->_modules)) {
			return false;
		}

		foreach ($this->_modules as $module) {
			$module->shutdown();
		}
		
		$this->setVar('keywords', implode(',', array_unique((array)$this->getVar('keywords'))));

		$this->_sqlLog();
		$this->setVar('appShutdownTime', number_format((self::getmicrotime() - $this->_shutdownStartTime), 4, '.', ''));
		$this->setVar('globalAppTime', number_format((self::getmicrotime() - $this->_appStartTime), 4, '.', ''));

		$this->getTemplateEngine()->display();

		$this->getDB()->closeConnection();
		return true;
	}

	private function _sqlLog()
	{
		$db = $this->getDB();
		$profiler = $db->getProfiler();

		if (empty($profiler) || !$profiler->getEnabled()) {
			return false;
		}

		$queries = $profiler->getQueryProfiles(null, true);
		if (empty($queries)) {
			return false;
		}

		$db->beginTransaction();
		foreach ($queries as $query) {
			if ($query->getElapsedSecs() < PTA_PROFILER_TIME) {
				continue;
			}

			$db->insert(
				'SQLLOG',
				array(
					'SQLLOG_QUERY' => $query->getQuery(),
					'SQLLOG_QUERYTYPE' => $query->getQueryType(),
					'SQLLOG_RUNTIME' => $query->getElapsedSecs(),
					'SQLLOG_SITEID' => PTA_SITE_ID
				)
			);
		}
		$db->commit();

		if (isset($_REQUEST['sql_debug'])) {
			foreach ($queries as $query) {
				var_dump($query->getQuery() . '; runTime = ' . number_format($query->getElapsedSecs(), 4, '.', ''));
			}
		}

		$this->setVar('sqlQueriesCnt', $profiler->getTotalNumQueries());
		$this->setVar('sqlRunTime', number_format($profiler->getTotalElapsedSecs(), 4, '.', ''));
		$this->setVar('memoryUsage', memory_get_peak_usage(true));
		$this->setVar('debug', PTA_APP_DEBUG);

		return true;
	}

	/**
	 * Get Database instance
	 *
	 * @return Zend_Db_Adapter_Abstract
	 */
	public function getDB()
	{
		return Zend_Registry::get('db');
	}

	public function setDB($db)
	{
		Zend_Registry::set('db', $db);
	}

	/**
	 * Get App Template Engine 
	 *
	 * @return PTA_TemplateEngine
	 */
	public function getTemplateEngine()
	{
		return $this->_templateEngine;
	}

	/**
	 * Set App Template Engine
	 *
	 * @param PTA_TemplateEngine $engine
	 */
	public function setTemplateEngine(PTA_TemplateEngine $engine)
	{
		$this->_templateEngine = $engine;
	}

	public function insertModule($prefix, $module, $isActive = false)
	{
		if (isset($this->_modules[$prefix] )) {
			return true;
		}
		
		if (class_exists($module, true)) {
			$this->_modules[$prefix] = new $module($prefix);
			if ($isActive) {
				$this->setActiveModule($prefix);
			}
			return true;
		}
		
		return false;
	}

	/**
	 * Get Active Module
	 *
	 * @return PTA_WebModule
	 */
	public function getActiveModule()
	{
		foreach ($this->getModules() as $module) {
			if ($module->isActive()) {
				return $module;
			}
		}

		return false;
	}
	
	public function setActiveModule($modulePrefix)
	{
		if (($module = $this->getModule($modulePrefix))) {
			if (($activeModule = $this->getActiveModule())) {
				$activeModule->setActive(false);
			}
			$module->setActive(true);
			return true;
		}

		return false;
	}
	
	public function getModules()
	{
		return $this->_modules;
	}

	/**
	 * Return App Module
	 *
	 * @param string $prefix
	 * @return PTA_WebModule
	 */
	public function getModule($prefix)
	{
		return (isset($this->_modules[$prefix]) ? $this->_modules[$prefix] : false);
	}
	
	/**
	 * Get App Router
	 *
	 * @return PTA_Router
	 */
	public function getRouter()
	{
		return $this->_router;
	}
	
	/**
	 * Set App Router
	 *
	 * @param PTA_Router $router
	 */
	public function setRouter(PTA_Router $router)
	{
		$this->_router = $router;
	}

	/**
	 * Get variable from http request
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getHttpVar($key, $withCookie = false)
	{
		if (($value = parent::getHttpVar($key))) {
			return $value;
		}
		
		if (($value = $this->getRouter()->getQueryVar($key))) {
			return $this->quote($value);
		}

		if ($withCookie && ($value = $this->getCookie($key))) {
			return $this->quote($value);
		}

		return null;
	}

	/**
	 * Set Var To REQUEST array
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setHttpVar($key, $value)
	{
		parent::setHttpVar($this->getPrefix() . "_$key", $value);
	}

	/**
	 * get variable from cookie
	 *
	 * @param string $cookieName
	 * @return string
	 */
	public function getCookie($cookieName)
	{
		$name = $this->getPrefix() . "_$cookieName";
		if (isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		}

		return null;
	}
	
	/**
	 * Set cookie for PTA application
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param int $expire
	 * @param string $path
	 * @param string $domain
	 * @param boolean $secure
	 * @param boolean $httponly
	 * @return boolean
	 */
	public function setCookie($name, $value, $expire= null, $path = '/', $domain = '', $secure= false, $httponly= false)
	{
		if (empty($domain)) {
			$domain = $_SERVER['SERVER_NAME'];
		}
		
		if (is_null($expire)) {
			if (defined('PTA_COOKIE_EXPIRE_TIME')) {
				$expire =time() + PTA_COOKIE_EXPIRE_TIME;
			} else {
				$expire = 0;
			}
		}

		$name = $this->getPrefix() . "_{$name}";
		return setcookie($name, $value, $expire, $path);
		//return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}
	
	/**
	 * Get Base App URL
	 *
	 * @return string
	 */
	public function getBaseUrl()
	{
		if (($baseUrl = $this->getVar('url'))) {
			return $baseUrl;
		}

		return (defined('PTA_BASE_URL') ? PTA_BASE_URL : $_SERVER['HTTP_HOST']);
	}

	public function setBaseUrl($url)
	{
		$this->setVar('url', $url);
	}

	/**
	 * Add New App Message 
	 *
	 * @param int $type
	 * @param string $message
	 */
	public function message($type, $message)
	{
		$messages = (array)$this->getVar('messages');
		$messages[] = array(
			'type' => $type,
			'message' => $message
		);
		$this->setVar('messages', $messages);
	}

	public function getVisual($prefix, $fromModules = false)
	{
		if ($fromModules) {
			foreach ($this->getModules() as $module) {
				$visualElem = $module->getVisual($prefix);
				if (!empty($visualElem)) {
					return $visualElem;
				}
			}
		}

		return parent::getVisual($prefix);
	}
}
