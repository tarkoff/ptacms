<?php
/**
 * Main app module
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_App extends PTA_WebModule 
{
	protected $_templateEngine;
	protected $_modules = array();
	protected $_user;

	private $_appStartTime;
	private $_initStartTime;
	private $_runStartTime;
	private $_shutdownStartTime;

	protected $_controller;
	protected $_action;
	protected $_router;
	protected $_httpClient;

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

	public static function getInstance()
	{
		if (empty(self::$_instance)) {
			self::$_instance = new self('app');
		}
		
		return self::$_instance;
	}

	public function setUser(PTA_User $user)
	{
		if ($this->setCookie('SID', $user->getSessionHash())) {
			$user->saveUserSession();
			$this->_user = $user;
		}
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
			$module->init();
		}

		return true;
	}
	
	public function init()
	{
		$this->_initStartTime = self::getmicrotime();
		
		parent::init();
		
		$this->_templateEngine->init();
		$this->initModules($this->getModules());

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
			$module->run();
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

		$this->_sqlLog();

		$this->setVar('appShutdownTime', number_format((self::getmicrotime() - $this->_shutdownStartTime), 4, '.', ''));
		$this->setVar('globalAppTime', number_format((self::getmicrotime() - $this->_appStartTime), 4, '.', ''));

		$this->getTemplateEngine()->display();

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
			$data = array(
						'SQLLOG_QUERY' => $query->getQuery(),
						'SQLLOG_QUERYTYPE' => $query->getQueryType(),
						'SQLLOG_RUNTIME' => $query->getElapsedSecs()
					);
			
			$db->insert('SQLLOG', $data);
		}
		$db->commit();

		$this->setVar('sqlQueriesCnt', $profiler->getTotalNumQueries());
		$this->setVar('sqlRunTime', number_format($profiler->getTotalElapsedSecs(), 4, '.', ''));
		$this->setVar('memoryUsage', memory_get_peak_usage(true));
		$this->setVar('debug', APPDEBUG);

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

	public function insertModule($prefix, $module)
	{
		if (isset($this->_modules[$prefix] )) {
			return false;
		}
		
		try {
			$this->_modules[$prefix] = new $module($prefix);
		} catch (Zend_Exception $e){
			echo $e->getMessage();
		}
		
		return true;
	}

	/**
	 * Get Active Module
	 *
	 * @return PTA_WebModule
	 */
	public function getActiveModule()
	{
		$activeModule = $this->getModule('activeModule');
		
		return (isset($activeModule) ? $activeModule : false);
	}
	
	public function setActiveModule($modulePrefix)
	{
		if (($module = $this->getModule($modulePrefix))) {
			$this->_modules['activeModule'] = $module;
			return true;
		}

		return $this->insertModule($modulePrefix, $modulePrefix);
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
	public function getHttpVar($key, $withCookie = true)
	{
		if (($value = parent::getHttpVar($key))) {
			return $value;
		}
		
		if (($value = $this->getRouter()->getQueryVar($key))) {
			return $value;
		}

		if ($withCookie) {
			if (($value = $this->getCookie($key))) {
				return $value;
			}
		}

		return null;
	}

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
			if (defined('COOKIE_EXPIRE_TIME')) {
				$expire =time() + COOKIE_EXPIRE_TIME;
			} else {
				$expire = 0;
			}
		}

		$name = $this->getPrefix() . "_{$name}";
		return setcookie($name, $value, $expire, $path);
		//return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}
	
	public function getBaseUrl()
	{
		return (defined('BASEURL') ? BASEURL : $_SERVER['HTTP_HOST']);
	}
}
