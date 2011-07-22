<?php
/**
 * ixMarket Offer Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_MixMarket
 * @copyright  Copyright (c) 2009-2011 KIT Studio
 * @license    New BSD License
 * @version    $Id: Product.php 407 2010-05-30 16:03:08Z TPavuk $
 */

class KIT_MixMarket_Offer extends KIT_Model_Abstract
{
	private $_title;
	private $_brandId;
	private $_alias;
	private $_shortDescr;
	private $_date;
	private $_url;
	private $_driversUrl;
	private $_authorId;
	/**
	 * @var KIT_Catalog_Product_Category
	 */
	private $_category;
	/**
	 * @var KIT_Catalog_Product_Castom_Fields
	 */
	private $_customFields;

	public function setId($id)
	{
		$this->_id = $id;
		$this->getCategory()->setProductId($this->_id);
		$this->getCustomFields()->setProductId($this->_id);
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function setUrl($url)
	{
		$this->_url = $url;
	}

	public function getDriversUrl()
	{
		return $this->_driversUrl;
	}

	public function setDriversUrl($url)
	{
		$this->_driversUrl = $url;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getAlias()
	{
		return $this->_alias;
	}

	public function setAlias($alias)
	{
		$this->_alias = $alias;
	}

	public function getShortDescr()
	{
		return $this->_shortDescr;
	}

	public function setShortDescr($descr)
	{
		$this->_shortDescr = $descr;
	}

	public function getBrandId()
	{
		return $this->_brandId;
	}

	public function setBrandId($id)
	{
		$this->_brandId = (int)$id;
	}

	public function getDate()
	{
		return $this->_date;
	}

	public function setDate($date)
	{
		$this->_date = $date;
	}

	public function getAuthorId()
	{
		return $this->_authorId;
	}

	public function setAuthorId($id)
	{
		$this->_authorId = (int)$id;
	}

}
