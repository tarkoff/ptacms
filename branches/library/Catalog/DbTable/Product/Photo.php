<?php
/**
 * Catalog Product Photo Database Table
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
 * @version    $Id: Photo.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_DbTable_Product_Photo extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_PHOTOS';
	protected $_primary = 'PHOTOS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$this->setViewSelect(
			$this->getAdapter()->select()->from(
				array('phs' => $this->_name),
				$this->getFields(false)
			)
		);
	}

	/**
	 * Get Product Default Photo
	 *
	 * @param int $productId
	 * @param boolean $fullRecord
	 * @return int|Zend_Db_Table_Row_Abstract
	 */
	public function getDefaultPhoto($productId, $fullRecord = false)
	{
		$productId = (int)$productId;
		if (empty($productId)) {
			return false;
		}

		$select = $this->select()->from(
			$this->_name,
			array('PHOTOS_ID')
		);
		$select->where('PHOTOS_PRODUCTID = ' . $productId);
		$select->where('PHOTOS_ISDEFAULT = 1');

		if ($fullRecord) {
			$select->columns($this->getFields(false));
			return $this->fetchRow($select);
		} else {
			return $this->getAdapter()->fetchOne($select->limit(1));
		}
	}
	
	/**
	 * Set Product Default Photo
	 *
	 * @param int $productId
	 * @param int $photoId
	 * @return boolean
	 */
	public function setDefaultPhoto($productId, $photoId)
	{
		$productId  = (int)$productId;
		$photoId    = (int)$photoId;
		if (empty($productId) || empty($photoId)) {
			return false;
		}
		
		$this->unsetDefaultPhoto($productId);
		return $this->update(
			array('PHOTOS_ISDEFAULT' => 1),
			array(
				'PHOTOS_ID = ' . $photoId,
				'PRODUCTCATEGORIES_PRODUCTID = ' . $productId
			)
		);
	}
	

	public function unsetDefaultPhoto($productId)
	{
		$productId  = (int)$productId;
		if (empty($productId)) {
			return false;
		}

		return $this->update(
			array('PHOTOS_ISDEFAULT' => 0),
			array('PHOTOS_PRODUCTID = ' . $productId)
		);
	}
	
	public function getProductPhotos($productId)
	{
		$productId = intval($productId);
		if (empty($productId)) {
			return false;
		}
		return $this->fetchAll('PHOTOS_PRODUCTID = ' . $productId);
	}
}
