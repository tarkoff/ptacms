<?php
/**
 * Catalog Product Table
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PRODUCTS';
	protected $_primary = 'PRODUCTS_ID';
	protected $_product;
	
	/**
	 * Get category fields with custom fields
	 *
	 * @return unknown
	 */
	public function getFields()
	{
		$fields = parent::getFields();

		if (!empty($this->_product)) {
			$fields = array_merge($fields, $this->_product->getCustomFields());
		}

		return $fields;
	}
	
	public function setProduct($product)
	{
		$this->_product = $product;
	}

	/**
	 * Return Zend_DB_Table_Select for catalog items
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Select
	 */
	public function getCatalogQuery($categoryId = null)
	{
		$catsTable = PTA_DB_Table::get('Catalog_Category');
		$prodsCatsTable = PTA_DB_Table::get('Catalog_Product_Category');
		$brandsTable = PTA_DB_Table::get('Catalog_Brand');
		//$photoTable = PTA_DB_Table::get('Catalog_Product_Photo');

		$select = $this->select()->from(array('prods' => $this->getTableName()));
		$select->setIntegrityCheck(false);

		$select->join(
			array('brands' => $brandsTable->getTableName()),
			'prods.'. $this->getFieldByAlias('brandId') 
			. ' = brands.' . $brandsTable->getPrimary(),
			array(
				$brandsTable->getFieldByAlias('alias'),
				$brandsTable->getFieldByAlias('title')
			)
		);

/*
		$select->joinLeft(
			array('photos' => $photoTable->getTableName()),
			'prods.' . $this->getPrimary() . ' = ' . $photoTable->getFieldByAlias('productId')
			. ' AND photos.' . $photoTable->getFieldByAlias('default') . ' = 1',
			array($photoTable->getFieldByAlias('photo'))
		);
*/
		$catsTableName = $catsTable->getTableName();
		$catsPrimaryField = $catsTable->getPrimary();

		$select->join(
			array('prodCats' => $prodsCatsTable->getTableName()),
			'prods.'. $this->getPrimary()
			. ' = prodCats.' . $prodsCatsTable->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('cats' => $catsTableName),
			'prodCats.'. $prodsCatsTable->getFieldByAlias('categoryId') . " = cats.{$catsPrimaryField}",
			array(
				$catsTable->getFieldByAlias('alias'),
				$catsTable->getFieldByAlias('title')
			)
		);

		if (!empty($categoryId)) {
			$select->where('cats.' . $catsTable->getPrimary() . ' in (?)', $categoryId);
		}

		return $select;
	}
}
