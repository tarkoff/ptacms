<?php
/**
 * MixMarket Offer
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Offer extends PTA_DB_Object
{

	private $_brandId;
	private $_advId;
	private $_cat;
	private $_src;
	private $_w;
	private $_h;
	private $_type;
	private $_name;
	private $_url;
	private $_currencyId;
	private $_desc;
	private $_price;

	public function getBrandId()
	{
		return $this->_brandId;
	}

	public function setBrandId($id)
	{
		$this->_brandId = (int)$id;
	}

	public function getAdvId()
	{
		return $this->_advId;
	}

	public function setAdvId($id)
	{
		$this->_advId = (int)$id;
	}

	public function getCat()
	{
		return $this->_advId;
	}

	public function setCat($id)
	{
		$this->_cat = (int)$id;
	}

	public function getSrc()
	{
		return $this->_src;
	}

	public function setSrc($src)
	{
		$this->_src = $src;
	}

	public function getW()
	{
		return $this->_w;
	}

	public function setW($w)
	{
		$this->_w = (int)$w;
	}

	public function getH()
	{
		return $this->_h;
	}

	public function setH($h)
	{
		$this->_h = (int)$h;
	}

	public function getType()
	{
		return $this->_type;
	}

	public function setType($type)
	{
		$this->_type = $type;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function setUrl($url)
	{
		$this->_url = $url;
	}

	public function getCurrencyId()
	{
		return $this->_currencyId;
	}

	public function setCurrencyId($id)
	{
		$this->_currencyId = (int)$id;
	}

	public function getDesc()
	{
		return $this->_desc;
	}

	public function setDesc($desc)
	{
		$this->_desc = $desc;
	}

	public function getPrice()
	{
		return $this->_price;
	}

	public function setPrice($price)
	{
		$this->_price = $price;
	}
}
