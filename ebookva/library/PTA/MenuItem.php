<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: MenuItem.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MenuItem
{
	private $_id = null;
	private $_title = null;
	
	public function __construct($id=null, $title=null)
	{
		if (!empty($id)) {
			$this->setId($id);
		}

		if (!empty($title)) {
			$this->setTitle($title);
		}
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function setId($id)	
	{
		$this->_id = intval($id);
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	public function setTitle($title)
	{
		$this->_title = $title;
	}
}
