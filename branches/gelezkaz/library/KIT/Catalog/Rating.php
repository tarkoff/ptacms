<?php
/**
 * Catalog Rating Model
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

class KIT_Catalog_Rating extends KIT_Model_Abstract
{
	private $_productId;
	private $_rating;
	PRIVATE $_ratesCnt;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = (int)$id;
	}

	public function getRating()
	{
		return $this->_rating;
	}

	public function setRating($rating)
	{
		$this->_rating = (int)$rating;
	}

	public function getRatesCnt()
	{
		return $this->_ratesCnt;
	}

	public function setRatesCnt($ratesCnt)
	{
		$this->_ratesCnt = (int)$ratesCnt;
	}

}
