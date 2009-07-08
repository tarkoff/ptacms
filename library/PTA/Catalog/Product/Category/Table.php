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
	public function getCategoriesByProductId($productId, $withProdsCnt = false)
	{
		if (empty($productId)) {
			return array();
		}

		$productCats = $this->findByFields(array('productId'), array($productId));

		$productCategories = array();
		if (!empty($productCats)) {
			$catsIds = array();
			$catIdField = $this->getFieldByAlias('categoryId');
			foreach ($productCats as $cat) {
				$catsIds[$cat[$catIdField]] = $cat[$catIdField];
			}

			$categoryTable =  PTA_DB_Table::get('Catalog_Category');
			$productCategories = $categoryTable->findByFields(array('id'), array($catsIds));

			if ($withProdsCnt && !empty($catsIds)) {
				$prodsCatsCnt = $this->getCategoryProductsCnt($catsIds);
				$catIdField = $categoryTable->getPrimary();
				if (!empty($prodsCatsCnt)) {
					foreach ($productCategories as &$cat) {
						if (isset($prodsCatsCnt[$cat[$catIdField]])) {
							$cat['PRODS_CNT'] = $prodsCatsCnt[$cat[$catIdField]];
						} else {
							$cat['PRODS_CNT'] = 0;
						}
					}
				}
			}
		}

		return $productCategories;
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
}
