<?php
/**
 * MixMarket Adv Region Geo Target
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Adv_Region_GeoTarget extends PTA_DB_Object
{
	private $_advId;
	private $_rgtId;

	public function getAdvId()
	{
		return $this->_advId;
	}

	public function setAdvId($advId)
	{
		$this->_advId = (int)$advId;
	}

	public function getRgtId()
	{
		return $this->_rgtId;
	}

	public function setRgtId($rgtId)
	{
		$this->_rgtId = (int)$rgtId;
	}
}
