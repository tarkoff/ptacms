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

abstract class PTA_Menu extends PTA_WebModule  
{
	private $_activeItem;
	private $_items = array();
	
	private $_saved = false;
		
	public function __construct($prefix, $tpl=null)
	{
		parent::__construct($prefix, $tpl);
	}
	
	public function getActiveMenuitem()	
	{
		return $this->_activeItem;
	}
	
	public function setActiveItem($activeItem)
	{
		if (in_array($activeItem, $this->getItems())) {
			$this->_activeItem = $activeItem;
			
			return true;
		}
		
		return false;
	}
	
	public function getItems()
	{
		return $this->_items;
	}
	
	public function addItem($item)
	{
		if (is_string($item)) {
			$this->_items[] = new PTA_MenuItem(null, $item);
			return true;
		}
		
		if (is_object($item) && ($item instanceof PTA_MenuItem)) {
			if (!in_array($item, $this->getItems())) {
				$this->_items[] = $item;
				
				return true;
			}
		}
		
		return false;
	}
	
	public function removeItem($item)
	{
		if (in_array($item, $this->getItems())) {
			unset($this->_items[$item]);
			return true;
		}
		
		return false;
	}
	
	public function saveMenu()
	{
		if ($this->getPeer()->save()) {
			$this->_saved = true;
			
			return true;
		}
		
		return false;
	}
	
	public function __destruct()
	{
		if (!$this->_saved) {
			$this->saveMenu();
		}
	}
}
