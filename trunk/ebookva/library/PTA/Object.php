<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_Object extends PTA_DB
{
	private $_prefix = null;
	private $_config = null;
	
	private $_inited = false;
	private $_runned = false;
	
	private $_vars = array();
	
	/**
 	 * getPrefix - return object prefix
	 *
	 * @method getPrefix
	 * @return string
	*/	
	public function getPrefix()
	{
		return $this->_prefix;
	}
	
	public function setPrefix($prefix)
	{
		$this->_prefix = $prefix;
	}
	
	public function getConfig()
	{
		return $this->_config;
	}
	
	public function setConfig($config)
	{
		$this->_config = $config;
	}
	
	public function init()
	{
	    $this->_inited = true;
	}

	public function run()
	{
	    $this->_runned = true;
	}

	public function shutdown()
	{
	}
	
	public function toString()
	{
	    $object = new stdClass();
	    
	    $object->prefix = $this->getPrefix();
	    $vars = $this->getVars();
	    
	    if (count($vars)) {
	        foreach ($vars as $prefix=>$value) {
	            $object->$prefix = $value;
	        }
	    }
	    
	    return $object;
	}
	
	public function getVars()
	{
		return $this->_vars;
	}
	
	public function setVar($prefix, $value)
	{
		$this->_vars[$prefix] = $value;
	}
	
	public function getVar($prefix)
	{
	    return (empty($this->_vars[$prefix]) ? null : $this->_vars[$prefix]);
	}
	
	public function setVars($vars)
	{
		if (is_array($vars)) {
			foreach ($vars as $key=>$value) {
				$this->setVar($key, $value);
			}
		} elseif (is_object($vars) && method_exists($vars, 'toArray')) {
			$this->setVars($vars->toArray());
		} elseif (is_string($vars)) {
			$this->setVar(PTA_App::getInstance()->getPrefix(), $vars);
		}
	}
	
	/**
	 * Get app instance
	 *
	 * @return PTA_App
	 */
	public function getApp()
	{
		return PTA_App::getInstance();
	}
	
	public function getHttpVar($name)
	{
	    if (isset($_REQUEST[$name])) {
	        return $_REQUEST[$name];
	    }
	    
	    return null;
	}
	
	public function isInited()
	{
	    return $this->_inited;
	}
	
	public function isRunned()
	{
	    return $this->_runned;
	}
	
	/**
	 * redirect to new location
	 *
	 * @param string $url
	 * @param int $timeout
	 */
	public function redirect($url, $timeout = 0)
	{
	    if (!empty($timeout)) {
	        sleep($timeout);
	    }
	    header("Location:$url");
	}
	
	public function message($alias, $params = null)
	{
	    
	}
	
	public function quote($value)
	{
		if (!is_numeric($value)) {
			$value = htmlspecialchars(strip_tags(trim($value)));
		}

		return $value;
	}
		
}
