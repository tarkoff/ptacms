<?php
/**
 * Menu Item Model
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
 * @version    $Id: Menu.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Default_Menu extends KIT_Model_Tree_Abstract
{
	protected $_title;
	protected $_alias;
	protected $_resourceId;

	public function getTitle()
	{
		return $this->_title;
	}
	
	public function setTitle($title)
	{
		$this->_title = $title;
	}
	
	public function getAlias()
	{
		return $this->_alias;
	}
	
	public function setAlias($alias)
	{
		$this->_alias = $alias;
	}

	public function getResourceId()
	{
		return $this->_resourceId;
	}
	
	public function setResourceId($id)
	{
		$this->_resourceId = $id;
	}
}