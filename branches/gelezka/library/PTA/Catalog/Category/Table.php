<?php
/**
 * Catalog Category Table
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_CATEGORIES';
	protected $_primary = 'CATEGORIES_ID';

	private static $_categoryChilds = array();

	/**
	 * Get childs for category by id
	 *
	 * @param int $categoryId
	 * @param boolean $onlyPublic
	 * @return array
	 */
	public function getChildsById($categoriesIds = null, $onlyPublic = true)
	{
		$categoriesIds = (array)$categoriesIds;

		$cacheKey = implode('_', $categoriesIds) . '_' . intval($onlyPublic);
		if (isset(self::$_categoryChilds[$cacheKey])) {
			return self::$_categoryChilds[$cacheKey];
		}

		$resultCategories = array();
		$tableName = $this->getTableName();
		$fields = array_values($this->getFields());

		$categoryIdField = $this->getPrimary();
		$parentField = $this->getFieldByAlias('parentId');
		$publicField = $this->getFieldByAlias('isPublic');

		$this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
		do {
			$select = $this->select()->from($tableName, $fields);

			$select->where($parentField . ' in (?)', $categoriesIds);
			if ($onlyPublic) {
				$select->where($publicField . ' = 1');
			}
			$select->order($parentField);

			$res = $this->getAdapter()->fetchAssoc($select);
			$resultCategories = array_merge($resultCategories, $res);

			$categoriesIds = array();
			foreach ($res as $category) {
				if ($category[$parentField]) {
					$categoriesIds[] = $category[$categoryIdField];
				}
			}
		} while ($categoriesIds);
		
		self::$_categoryChilds[$cacheKey] = $resultCategories;
		return $resultCategories;
	}

	/**
	 * Get category childs by category id
	 *
	 * @param string $categoryAlias
	 * @param boolean $onlyPublic
	 * @return array
	 */
	public function getChildsByAlias($categoryAlias, $onlyPublic = true)
	{
		if (empty($categoryAlias)) {
			return array();
		}

		$select = $this->select()->from($this->getTableName(), $this->getPrimary());
		$select->where($this->getFieldByAlias('alias') . ' = ?', $categoryAlias);

		return $this->getChildsById(
			$this->getAdapter()->fetchOne($select),
			$onlyPublic
		);

	}

	/**
	 * Get super root category for current category by cayegory id
	 *
	 * @param array $categoryId
	 * @param boolean $allParents
	 * @return array
	 */
	public function getRootCategory($categoryId, $allParents = false)
	{
		if (empty($categoryId)) {
			return array();
		}

		$categoryId = (array)$categoryId;
		$parentField = $this->getFieldByAlias('parentId');
		$idField = $this->getPrimary();

		$categories = array();
		if ($allParents) {
			do {
				$parentCategories = $this->findByFields(array('id'), array($categoryId));
				$categoryId = array();
				foreach ($parentCategories as $cat) {
					$categories[$cat[$idField]] = $cat;
					$categoryId[$cat[$idField]] = intval($cat[$parentField]);
				}
			} while (!empty($categoryId));
		} else {
			do {
				$parentCategories = $this->findByFields(array('id'), array($categoryId));
				$categoryId = $categories = array();
				foreach ($parentCategories as $cat) {
					$categories[$cat[$idField]] = $cat;
					$categoryId[$cat[$idField]] = intval($cat[$parentField]);
				}
			} while (!empty($categoryId));
		}

		return $categories;
	}
}
