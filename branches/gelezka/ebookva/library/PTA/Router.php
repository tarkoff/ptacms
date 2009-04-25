<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Router extends PTA_Object {
	private static $_instance;	

	const SITEPART = 1;
	const ADMINPART = 2;

	//Dir, File, Mix
	protected $_routeMode = 'File';
	
	protected $_sitePart = self::SITEPART;
	protected $_activeAction = null;
	protected $_controller = null;
	protected $_vars = null;
	
	private function __construct() {
	}

	private function __clone() {
	}

	/**
	 * return router instance
	 *
	 * @return PTA_Router
	 */
	public static function getInstance()
	{
		if (!self::$_instance instanceof self) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	public function parseRoute($route=null)
	{
		if(!empty($route)){
			$route = parse_url($route);
		}else{
			$route = parse_url($_SERVER['REQUEST_URI']);
		}

		if (empty($route['path'])) return ;
		$route['path'] = trim($route['path'], "/");
		$parts = explode("/", $route['path']);

		if ($this->isAdminPart($parts)) {
			$this->_sitePart = self::ADMINPART;
			array_shift($parts);
		}

		$partsCount = count($parts);
		if ($partsCount<2) {
			$this->_controller = empty($parts[0]) ? '' : ucfirst($parts[0]);
			array_shift($parts);
		} else {
			$this->_controller = empty($parts[0]) ? '' : ucfirst($parts[0]);
			array_shift($parts);
			$this->_activeAction = empty($parts[0]) ? '' : ucfirst($parts[0]);
			array_shift($parts);
		}

		if (count($parts)) {
			$this->_vars = $this->_getQueryVars($parts);
		}
	}

	public function isAdminPart($parts)
	{
		return (bool)(strtolower($parts[0])=='admin');
	}

	public function getSitePart()
	{
		return $this->_sitePart;
	}

	/**
	 * parse parameters setted in url
	 *
	 * @param array $query
	*/
	private function _getQueryVars($queryVars)
	{
		$vars = array();
		while (current($queryVars)) {
			$key = $this->quote(current($queryVars));
			$value = $this->quote(next($queryVars));
			$vars[$key] = $value;
			$_REQUEST[$key] = $value;
			array_shift($queryVars);
			array_shift($queryVars);
		}

		return $vars;  	
	}

	/**
	 * return all query vars
	 *
	 * @return array|null
	*/
	public function getQueryVars()
	{
		return $this->_vars;
	}

	/**
	 * return query var by key
	 *
	 * @param string $key
	 * @return string|numeric|null
	 */
	public function getQueryVar($key)
	{
		if (isset($this->_vars[$key])) {
			return $this->_vars[$key];
		}
		return null;
	}

	public function getActiveController()
	{
		return $this->_controller;
	}

	public function getActiveAction()
	{
		return $this->_activeAction;
	}

}
