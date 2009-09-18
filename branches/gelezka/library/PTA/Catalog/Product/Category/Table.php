<?php
/**
 * Catalog Product Category Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 65 2009-06-04 21:30:33Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Category_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PRODUCTCATEGORIES';
	protected $_primary = 'PRODUCTCATEGORIES_ID';

	public function saveProductCategories($productId, $categories)
	{
		if (empty($productId) || empty($categories)) {
			return false;
		}
		
		$productId = intval($productId);
		$categories = (array)$categories;
		
		$productField = $this->getFieldByAlias('productId');
		$categoryField = $this->getFieldByAlias('categoryId');
		
		$this->getAdapter()->beginTransaction();
		$this->delete($productField . ' = ' . $productId);
		foreach ($categories as $categoryId) {
			if (!empty($categoryId)) {
				$this->insert(
					array(
						$categoryField => intval($categoryId),
						$productField => $productId
					)
				);
			}
		}
		return $this->getAdapter()->commit();
	}
	
	public function getCategoryProductsCnt($categoriesIds = null)
	{
		$categoriesIds = (array)$categoriesIds;
		
		$categoryIdField = $this->getFieldByAlias('categoryId');
		$select = $this->select()->from(
			$this->getTableName(),
			array($categoryIdField, 'count(*) as PRODUCTS_CNT')
		);
		$select->group($categoryIdField);
		
		if (!empty($categoriesIds)) {
			$select->having($categoryIdField . ' in (?)', $categoriesIds);
		}
		return $this->getAdapter()->fetchPairs($select);
	}
	
	/**
	 * Get product categories
	 *
	 * @param int $productId
	 * @param boolean $withProdsCnt
	 * @return array
	 */
	public function getProductCategories($productId, $withProdsCnt = false)
	{
		if (empty($productId)) {
			return array();
		}

		$categoryTable =  PTA_DB_Table::get('Catalog_Category');

		$select = $this->select()->from(array('cats' => $categoryTable->getTableName()));
		$select->setIntegrityCheck(false);

		$select->join(
			array('prodCats' => $this->getTableName()),
			'cats.' . $categoryTable->getPrimary() 
			. ' = prodCats.' . $this->getFieldByAlias('categoryId'),
			array($this->getFieldByAlias('isDefault'))
		);

		$select->where(
			'prodCats.' . $this->getFieldByAlias('productId') . ' = ?',
			intval($productId)
		);
		
		$productCats = $this->fetchAll($select)->toArray();

		if (!empty($productCats)) {
			$catsIds = array();
			$catIdField = $categoryTable->getPrimary();
			foreach ($productCats as $cat) {
				$catsIds[$cat[$catIdField]] = $cat[$catIdField];
			}

			if ($withProdsCnt && !empty($catsIds)) {
				$prodsCatsCnt = $this->getCategoryProductsCnt($catsIds);
				$catIdField = $categoryTable->getPrimary();
				if (!empty($prodsCatsCnt)) {
					foreach ($productCats as &$cat) {
						if (isset($prodsCatsCnt[$cat[$catIdField]])) {
							$cat['PRODS_CNT'] = $prodsCatsCnt[$cat[$catIdField]];
						} else {
							$cat['PRODS_CNT'] = 0;
						}
					}
				}
			}
		}

		return $productCats;
	}
	
	public function resetCategories($productId)
	{
		if (empty($productId)) {
			return false;
		}

		return $this->delete(
			$this->getFieldByAlias('productId') . ' = ' . intval($productId)
		);
	}

	/**
	 * Get default product category
	 *
	 * @param int $productIds
	 * @param boolean $onlyIds
	 * 
	 * @return array
	 */
	public function getDefaultCategory($productIds, $onlyIds = true)
	{
		$productIds = (array)$productIds;
		if (empty($productIds)) {
			return array();
		}
		
		$catIdField = $this->getFieldByAlias('categoryId');
		$prodIdField = $this->getFieldByAlias('productId');

		$select = $this->select()->from(
			array('prodCats' => $this->getTableName()),
			array($prodIdField, $catIdField)
		);

		$productIds = array_map('intval', $productIds);
		$select->where($prodIdField . ' in (?) ', $productIds);
		$select->where($this->getFieldByAlias('isDefault') . ' = 1');

		if ($onlyIds) {
			return $this->getAdapter()->fetchPairs($select);
		} else {
			$catsTable = self::get('Catalog_Category');
			$select->join(
				array('cats' => $catsTable->getTableName()),
				'cats.' . $catsTable->getPrimary() . ' = prodCats.' . $catIdField,
				array($catsTable->getFieldByAlias('alias'), $catsTable->getFieldByAlias('title'))
			);
			$select->setIntegrityCheck(false);
			return $this->fetchAll($select)->toArray();
		}
	}

	public function setDefaultCategory($productId, $categoryId)
	{
		if (empty($productId) || empty($categoryId)) {
			return false;
		}

		return $this->update(
			array($this->getFieldByAlias('isDefault') => '1'),
			$this->getFieldByAlias('productId') . ' = ' . intval($productId)
			. ' and ' . $this->getFieldByAlias('categoryId') . ' = ' . intval($categoryId)
		);
	}
}
