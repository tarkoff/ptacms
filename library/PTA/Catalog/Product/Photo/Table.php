<?php
/**
 * Product Photos Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Photo_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PHOTOS';
	protected $_primary = 'PHOTOS_ID';
	
	/**
	 * Get Product Default Photo
	 *
	 * @param int $productId
	 * @return array
	 */
	public function getDefaultPhoto($productId)
	{
		if (empty($productId)) {
			return false;
		}
		
		$photo = $this->findByFields(
			array('productId', 'default'),
			array(intval($productId), 1)
		);
		return current($photo);
	}

	public function setDefaultPhoto($photoId, $productId = null)
	{
		if (empty($photoId)) {
			return false;
		}
		
		$this->getAdapter()->beginTransaction();
		if (!empty($productId)) {
		$this->update(
			array($this->getFieldByAlias('default') => 0),
			$this->getFieldByAlias('productId') . ' = ' . intval($productId)
		);
		}
		
		$res = $this->update(
			array($this->getFieldByAlias('default') => 1),
			$this->getPrimary() . ' = ' . intval($photoId)
		);
		$this->getAdapter()->commit();
		return $res;
	}
	
	public function getPhotos($productId)
	{
		if (empty($productId)) {
			return false;
		}
		
		$photos = $this->findByFields(
			array('productId'),
			array(intval($productId))
		);
		return $photos;
	}
}
