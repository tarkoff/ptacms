<?php
/**
 * Product Statistic
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Stat extends PTA_DB_Object 
{

	private $_productId;
	private $_views;
	private $_downloads;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($value)
	{
		$this->_productId= (int)$value;
	}

	public function getTViews()
	{
		return $this->_views;
	}

	public function setViews($views)
	{
		$this->_views = (int)$views;
	}

	public function getDownloads()
	{
		return $this->_downloads;
	}

	public function setDownloads($downloads)
	{
		$this->_downloads = (int)$downloads;
	}
}
