<?php
/**
 * Users Database Table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Product.php 286 2010-03-18 23:22:45Z TPavuk $
 */

class KIT_Catalog_DbTable_Product extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_PRODUCTS';
	protected $_primary = 'PRODUCTS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$usersTable    = self::get('KIT_Default_DbTable_User');
		$catsTable     = self::get('KIT_Catalog_DbTable_Category');
		$brandsTable   = self::get('KIT_Catalog_DbTable_Brand');
		$prodCatsTable = self::get('KIT_Catalog_DbTable_Product_Category');

		$select = $this->getAdapter()->select();

		$select->from(
			array('prods' => $this->_name),
			array(
				'PRODUCTS_ID',
				'PRODUCTS_ALIAS',
				'PRODUCTS_BRANDID' => 'brands.' . $brandsTable->getFieldByAlias('title'),
				'PRODUCTS_TITLE',
				'PRODUCTS_CATEGORYID' => 'cats.' . $catsTable->getFieldByAlias('title'),
				'PRODUCTS_AUTHORID' => 'usr.' . $usersTable->getFieldByAlias('login'),
				'PRODUCTS_DATE'
			)
		);

		$select->join(
			array('brands' => $brandsTable->getTableName()),
			'prods.PRODUCTS_BRANDID = brands.' . $brandsTable->getPrimary(),
			array()
		);

		$select->join(
			array('pc' => $prodCatsTable->getTableName()),
			'prods.PRODUCTS_ID = pc.' . $prodCatsTable->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('cats' => $catsTable->getTableName()),
			'pc.' . $prodCatsTable->getFieldByAlias('categoryId') . ' = cats.' . $catsTable->getPrimary(),
			array()
		);

		$select->join(
			array('usr' => $usersTable->getTableName()),
			'prods.PRODUCTS_AUTHORID = usr.' . $usersTable->getPrimary(),
			array()
		);
		
		$select->where('pc.' . $prodCatsTable->getFieldByAlias('isDefault') . ' = 1');

		$this->setViewSelect($select);
	}

	/**
	 * Remove record from database by primary key
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function removeById($id)
	{
		if (empty($id)) {
			return false;
		}

		$photosTable = self::get('KIT_Catalog_DbTable_Product_Photo');

		$fileField = $photosTable->getFieldByAlias('file');

		$rootDir = realpath(APPLICATION_PATH . '/../public');
		foreach ($photosTable->getProductPhotos($id) as $photo) {
			$fileName = $rootDir . '/' . ltrim($photo->$fileField, '/');
			Zend_Registry::get('logger')->err($fileName);
			if (!unlink($fileName)) {
				throw new Zend_Exception('Cannot delete photo: ' . $fileName);
			}
		}

		return parent::removeById($id);
	}

	/**
	 * Get common catalog select object
	 *
	 * @return Zend_Db_Select
	 */
	public function getCatalogSelect()
	{
		$catsTable     = self::get('KIT_Catalog_DbTable_Category');
		$brandsTable   = self::get('KIT_Catalog_DbTable_Brand');
		$photosTable   = self::get('KIT_Catalog_DbTable_Product_Photo');
		$prodCatsTable = self::get('KIT_Catalog_DbTable_Product_Category');

		$select = $this->select()
					   ->from(
							array('prods' => $this->getTableName()),
							array('PRODUCTS_ID',
								  'PRODUCTS_ALIAS',
								  'PRODUCTS_TITLE',
								  'PRODUCTS_SHORTDESCR',
								  'PRODUCTS_DATE'))
					   ->setIntegrityCheck(false);
		
		$select->join(
			array('brands' => $brandsTable->getTableName()),
			'prods.PRODUCTS_BRANDID = brands.' . $brandsTable->getPrimary(),
			array($brandsTable->getFieldByAlias('title'))
		);

		$select->joinLeft(
			array('photos' => $photosTable->getTableName()),
			'(prods.PRODUCTS_ID = photos.' . $photosTable->getFieldByAlias('productId')
			. ' AND photos.' . $photosTable->getFieldByAlias('isDefault') . ' = 1)',
			array($photosTable->getFieldByAlias('file'))
		);

		$select->join(
			array('pc' => $prodCatsTable->getTableName()),
			'prods.PRODUCTS_ID = pc.' . $prodCatsTable->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('cats' => $catsTable->getTableName()),
			'pc.' . $prodCatsTable->getFieldByAlias('categoryId') . ' = cats.' . $catsTable->getPrimary(),
			array($catsTable->getFieldByAlias('alias'), $catsTable->getFieldByAlias('title'))
		);

		$select->where('pc.' . $prodCatsTable->getFieldByAlias('isDefault') . ' = 1');

		return $select;
	}

	/**
	 * Get most popular products in this month
	 *
	 * @param int $limit
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getPopular($limit = 10)
	{
		$limit = (int)$limit;
		!empty($limit) || $limit = 10;

		$statsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Stat');
		
		$select = $this->getCatalogSelect();
		$select->join(
			array('stats' => $statsTable->getTableName()),
			'prods.PRODUCTS_ID = stats.' . $statsTable->getPrimary(),
			array()
		);
		$select->order('stats.' . $statsTable->getFieldByAlias('views') . ' DESC');
		return $this->fetchAll($select->limit($limit));
	}
	
	/**
	 * Get newest products
	 *
	 * @param int $limit
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getNewest($limit = 10)
	{
		$limit = (int)$limit;
		!empty($limit) || $limit = 10;

		$select = $this->getCatalogSelect()->order(array('prods.PRODUCTS_DATE DESC'));
		return $this->fetchAll($select->limit($limit));
	}
}

