<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATEGORIES';
	protected $_primary = 'CATEGORIES_ID';

	public function getCategoryById($categoryId)
	{
		return $this->find($categoryId)->toArray();
	}

	public function getCategoriesByRootId($categoryId = 0, $onlyPublic = true)
	{
		$select = $this->select()->from(
			$this->getTableName(),
			$this->getFieldsByAliases(array('alias', 'title'))
		);

		$select->where($this->getFieldByAlias('parentId') . ' = ' . intval($categoryId));
		if ($onlyPublic) {
			$select->where($this->getFieldByAlias('isPublic') . ' = 1');
		}

		$this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
		return $this->getAdapter()->fetchPairs($select);
	}

	public function getCategoriesByRootAlias($categoryAlias, $onlyPublic = true)
	{
		if (empty($categoryAlias)) {
			return array();
		}

		$select = $this->select()->from(
			array('cats1' => $this->getTableName()),
			$this->getFieldsByAliases(array('alias', 'title'))
		);

		$select->join(
			array('cats2' => $this->getTableName()),
			'cats1.' . $this->getFieldByAlias('parentId') . ' = cats2.' . $this->getPrimary(),
			array()
		);

		$select->where('cats2.' . $this->getFieldByAlias('alias') . ' = ?', $categoryAlias);

		if ($onlyPublic) {
			$select->where('cats1.' . $this->getFieldByAlias('isPublic') . ' = 1');
		}

		$this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
		return $this->getAdapter()->fetchPairs($select);
	}
	
	public function getRootCategory($categoryId, $allParent = false)
	{
		if (empty($categoryId)) {
			return array();
		}

		$parentField = $this->getFieldByAlias('parentId');

		$categories = array();
		if ($allParent) {
			do {
				$categories[$categoryId] = current($this->find(intval($categoryId))->toArray());
				$categoryId = $categories[$categoryId][$parentField];
			} while (!empty($categoryId));
		} else {
			do {
				$categories = current($this->find(intval($categoryId))->toArray());
				$categoryId = $categories[$parentField];
			} while (!empty($categoryId));
		}

		return $categories;
	}
}
