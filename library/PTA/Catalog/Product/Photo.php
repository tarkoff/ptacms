<?php
/**
 * Product Photo
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Photo extends PTA_DB_Object 
{

	private $_productId;
	private $_default = 0;
	private $_photo;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($value)
	{
		$this->_productId= (int)$value;
	}

	public function getDefault()
	{
		return $this->_default;
	}

	public function setDefault($isMain)
	{
		$this->_default = (int)$isMain;
	}

	public function getPhoto()
	{
		return $this->_photo;
	}

	public function setPhoto($photo)
	{
		$this->_photo = $photo;
	}

	/**
 	 * Remove object from database
	 *
	 * @method remove
	 * @access public
	 * @return boolean
	*/	
	public function remove()
	{
		$photo = $this->getPhoto();
		if (parent::remove()) {
			return PTA_Util::unlink(PTA_CONTENT_PATH . '/' . $photo);
		}

		return false;
	}
}
