<?php
/**
 * MixMarket Adv Region Delivery
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Adv_Region_Delivery extends PTA_DB_Object
{
	private $_advId;
	private $_rdId;

	public function getAdvId()
	{
		return $this->_advId;
	}

	public function setAdvId($advId)
	{
		$this->_advId = (int)$advId;
	}

	public function getRdId()
	{
		return $this->_rdId;
	}

	public function setRdId($rdId)
	{
		$this->_rdId = (int)$rdId;
	}
}
