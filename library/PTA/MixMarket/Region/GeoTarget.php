<?php
/**
 * MixMarket Region Geo Target
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Region_GeoTarget extends PTA_DB_Object
{
	private $_pid;
	private $_title;

	public function getPid()
	{
		return $this->_pid;
	}

	public function setPid($pid)
	{
		$this->_pid = (int)$pid;
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
