<?php
/**
 * Catalog Price Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Field.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_Price extends KIT_Model_Abstract
{
	private $_productId;
	private $_createDate;
	private $_actualTo;
	private $_cost;
	private $_author;
	private $_currencyId;
	private $_descr;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = (int)$id;
	}

	public function getCreateDate()
	{
		return $this->_createDate;
	}

	public function setCreateDate($date)
	{
		$this->_createDate = $date;
	}

	public function getActualTo()
	{
		return $this->_actualTo;
	}

	public function setActualTo($date)
	{
		$this->_actualTo = $date;
	}

	public function getCost()
	{
		return $this->_cost;
	}

	public function setCost($cost)
	{
		$this->_cost = (float)$cost;
	}

	public function getAuthor()
	{
		return $this->_author;
	}

	public function setAuthor($author)
	{
		$this->_author = $author;
	}

	public function getCurrencyId()
	{
		return $this->_currencyId;
	}

	public function setCurrencyId($id)
	{
		$this->_currencyId = (int)$id;
	}

	public function getDescr()
	{
		return $this->_descr;
	}

	public function setDescr($descr)
	{
		$this->_descr = $descr;
	}
}
