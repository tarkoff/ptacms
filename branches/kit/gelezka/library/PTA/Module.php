<?php
/**
 *  App Module
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_Module extends PTA_Object 
{
	private $_isActive = false;
	private $_publicAccess = true;

	public function __construct($prefix)
	{
		$this->setPrefix($prefix);
	}

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		parent::run();
		
		if (!empty($this->_elements)) {
			foreach ($this->_elements as $element) {
				$element->run();
			}
		}
	}

	public function shutdown()
	{
		parent::shutdown();
	}

	public function getModules()
	{
		return $this->_subModules;
	}

	public function insertModule($prefix, $module)
	{
		$this->getApp()->insertModule($prefix, $module);
	}

	public function getModule($prefix)
	{
		return $this->getApp()->getModule($prefix);
	}
	
	/**
	 * Return TRUE if this module active
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->_isActive;
	}
	
	/**
	 * Set app module as active
	 *
	 * @param boolean $active
	 */
	public function setActive($active = false)
	{
		$this->_isActive = (boolean)$active;
	}
	
	/**
	 * Return TRUE if module has public access
	 * 
	 * @return boolean
	 */
	public function isPublic()
	{
		return $this->_publicAccess;
	}
	
	/**
	 * Set access mode for module
	 * 
	 * @param boolean $publicAccess
	 */
	public function setPublic($publicAccess = true)
	{
		$this->_publicAccess = (boolean)$publicAccess;
	}

}
