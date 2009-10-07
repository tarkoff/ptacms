<?php
/**
 * Site Theme
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Theme extends PTA_DB_Object
{
	private $_siteId;
	private $_title;
	private $_active;

	public function getSiteId()
	{
		return $this->_siteId;
	}

	public function setSiteId($id)
	{
		$this->_siteId = intval($id);
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getActive()
	{
		return $this->_active;
	}

	public function setActive($active)
	{
		$this->_active = (int)$active;
	}

}
