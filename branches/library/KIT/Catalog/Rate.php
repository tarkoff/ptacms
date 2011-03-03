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

class KIT_Catalog_Rate extends KIT_Model_Abstract
{
	private $_productId;
	private $_rateDate;
	private $_oldRate = 0;
	private $_rate;
	private $_ip;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = (int)$id;
	}

	public function getRateDate()
	{
		return $this->_rateDate;
	}

	public function setRateDate($date)
	{
		$this->_rateDate = $date;
	}

	public function getRate()
	{
		return $this->_rate;
	}

	public function setRate($rate)
	{
		$rate = (int)$rate;
		if ($this->_rate != $rate) {
			$this->_oldRate = $this->_rate;
		}
		$this->_rate = $rate;
	}

	public function getIp()
	{
		return $this->_ip;
	}

	public function setIp($ip)
	{
		$this->_ip = $ip;
	}

	/**
	 * Save data to database
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function save($data = null)
	{
		if (parent::save($data)) {
			$rating = self::get('KIT_Catalog_Rating');
			$rating->loadByFields(array('productId' => $this->getProductId()));

			$rating->setProductId($this->getProductId());
			$rating->setRating($rating->getRating() - $this->_oldRate + $this->getRate());
			if (empty($this->_oldRate)) {
				$rating->setRatesCnt($rating->getRatesCnt() + 1);
			}
			return $rating->save();
		}
	}
}
