<?php
/**
 * Resource Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Resource.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Default_Resource extends KIT_Model_Abstract
{
	protected $_module;
	protected $_controller;
	protected $_action;

	public function getModule()
	{
		return $this->_module;
	}

	public function setModule($module)
	{
		$this->_module = $module;
		return $this;
	}

	public function getController()
	{
		return $this->_controller;
	}
	
	public function setController($controller)
	{
		$this->_controller = $controller;
	}
	
	public function getAction()
	{
		return $this->_action;
		return $this;
	}
	
	public function setAction($action)
	{
		$this->_action = $action;
		return $this;
	}
}
