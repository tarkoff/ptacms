<?php
/**
 * MixMarket Advertizer
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Advertizer extends PTA_DB_Object
{
	private $_title;
	private $_updated;

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getUpdated()
	{
		return $this->_updated;
	}

	public function setUpdated($updated)
	{
		$this->_updated = (boolean)$updated;
	}
}
