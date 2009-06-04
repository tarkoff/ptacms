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

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($value)
	{
		$this->_productId= (int)$value;
		//$this->_id = $this->_productId;
	}

	public function getViews()
	{
		return $this->_views;
	}

	public function setViews($views)
	{
		$this->_views = (int)$views;
	}

	public function save()
	{
		if (empty($this->_id) && !empty($this->_productId)) {
			return parent::save(true);
		}
		
		return parent::save();
	}
	
	public function loadById($id)
	{
		parent::loadById($id);
		$this->_id = $this->_productId;
	}

}
