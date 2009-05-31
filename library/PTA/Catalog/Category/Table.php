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

	public function getCategoryById($categoryId)
	{
		return $this->find($categoryId)->toArray();
	}

	/**
	 * Get childs for category by id
	 *
	 * @param int $categoryId
	 * @param boolean $onlyPublic
	 * @return array
	 */
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

	/**
	 * Get category childs by category id
	 *
	 * @param string $categoryAlias
	 * @param boolean $onlyPublic
	 * @return array
	 */
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
	
	/**
	 * Get super root category for current category by cayegory id
	 *
	 * @param int $categoryId
	 * @param boolean $allParents
	 * @return array
	 */
	public function getRootCategory($categoryId, $allParents = false)
	{
		if (empty($categoryId)) {
			return array();
		}

		$parentField = $this->getFieldByAlias('parentId');

		$categories = array();
		if ($allParents) {
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
