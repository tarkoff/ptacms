<?php
/**
 * MixMarket Currency
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Currency extends PTA_DB_Object
{

	private $_rate;

	public function getRate()
	{
		return $this->_rate;
	}

	public function setRate($rate)
	{
		$this->_rate = $rate;
	}
}
