<?php
/**
 * Product Price
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Price extends PTA_DB_Object
{

	private $_productId;
	private $_userId;
	private $_price;
	private $_descr;
	private $_url;
	private $_dateFrom;
	private $_dateTo;
	private $_currency;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = intval($id);
	}

	public function getUserId()
	{
		return $this->_userId;
	}

	public function setUserId($id)
	{
		$this->_userId = intval($id);
	}

	public function getCurrency()
	{
		return $this->_currency;
	}

	public function setCurrency($currency)
	{
		$this->_currency = intval($currency);
	}

	public function getPrice()
	{
		return $this->_price;
	}

	public function setPrice($price)
	{
		$this->_price = number_format($price, 2, '.', '');
		//$this->_price = (float)$price;
	}

	public function getDescr()
	{
		return $this->_descr;
	}

	public function setDescr($descr)
	{
		$this->_descr = $descr;
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function setUrl($url)
	{
		$this->_url = $url;
	}
	
	public function getDateFrom()
	{
		return $this->_dateFrom;
	}

	public function setDateFrom($date)
	{
		$this->_dateFrom = $date;
	}

	public function getDateTo()
	{
		return $this->_dateTo;
	}

	public function setDateTo($date)
	{
		$this->_dateTo = $date;
	}
}
