<?php
/**
 * Catalog Category Field Table
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_CategoryField_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_CATEGORIESFIELDS';
	protected $_primary = 'CATEGORIESFIELDS_ID';
	protected static $_fieldsCache = array();
	
	/**
	 * Get product fields by category id
	 *
	 * @param int $categoryId
	 * @param boolean $equal
	 * @param boolean $parentsFieldsToo
	 * @return array
	 */
	public function getFieldsByCategory($categoryId, $equal = true, $parentsFieldsToo = false)
	{
		if (empty($categoryId)) {
			return null;
		}

		$cachePrefix = $categoryId . '_' . intval($equal) . '_' . intval($parentsFieldsToo);
		if (isset(self::$_fieldsCache[$cachePrefix])) {
			return self::$_fieldsCache[$cachePrefix];
		}

		$categoriesIds = array();
		if ($parentsFieldsToo) {
			$categoryTable = PTA_DB_Table::get('Catalog_Category');
			$categories = $categoryTable->getRootCategory($categoryId, true);
			$categoryIdField = $categoryTable->getPrimary();
			foreach ($categories as $category) {
				$categoriesIds[] = $category[$categoryIdField];
			}
		} else {
			$categoriesIds[] = $categoryId;
		}
		
		$fieldsIds = array();
		$fieldIdField = $this->getFieldByAlias('fieldId');
		$categoryIdField = $this->getFieldByAlias('categoryId');
		
		$select = $this->select()->from($this->getTableName(), array($fieldIdField))
					->where("$categoryIdField in (?)", $categoriesIds);
		$fields = $this->fetchAll($select)->toArray();
		foreach ($fields as $field) {
			$fieldsIds[] = $field[$fieldIdField];
		}
		
		
		$resultSet = array();
		if (!empty($fieldsIds) || !$equal) {
			$resultSet = $this->_getFieldsByCategory($fieldsIds, $equal);
		}

		self::$_fieldsCache[$cachePrefix] = $resultSet;

		return $resultSet;
	}
	
	/**
	 * find all category fields
	 *
	 * @param int $categoryId
	 * @param boolean $equal
	 * @return array
	 */
	private function _getFieldsByCategory($fieldsIds, $equal = true)
	{
		$fieldsIds = (array)$fieldsIds;

		$fieldsTable = PTA_DB_Table::get('Catalog_Field');

		$select = $this->select()->from(
			array('fields' => $fieldsTable->getTableName())
			/*array_values($fieldsTable->getFields())*/
		);
		if ($equal) {
			$select->join(
				array('categoriesFields' => $this->getTableName()),
				'categoriesFields.' . $this->getFieldByAlias('fieldId') . ' = fields.' . $fieldsTable->getPrimary()
				/*array_values($this->getFields())*/
			);
			if (!empty($fieldsIds)) {
				$select->where('categoriesFields.' . $this->getFieldByAlias('fieldId') . ' in (?)', $fieldsIds);
			}
		} else {
			$select->joinLeft(
				array('categoriesFields' => $this->getTableName()),
				'categoriesFields.' . $this->getFieldByAlias('fieldId') . ' = fields.' . $fieldsTable->getPrimary()
				/*array_values($this->getFields())*/
			);
			if (!empty($fieldsIds)) {
				$select->where('categoriesFields.' . $this->getFieldByAlias('fieldId') . ' not in (?)', $fieldsIds);
				$select->orWhere('categoriesFields.' . $this->getFieldByAlias('categoryId') . ' is null');
			}
		}

		$select->order('categoriesFields.' . $this->getFieldByAlias('sortOrder'));
		$select->setIntegrityCheck(false);

		return $this->fetchAll($select)->toArray();
	}

	/**
	 * Remove all category fields by category id
	 *
	 * @param int $categoryId
	 * @return boolean
	 */
	public function clearbyCategoryId($categoryId)
	{
		return $this->clearByFields(
			array('categoryId' => (int)$categoryId)
		);
	}

	/**
	 * Add new fields to category
	 *
	 * @param int $categoryId
	 * @param array $fieldsIds
	 * @return boolean
	 */
	public function addCategoryFields($categoryId, $fieldsIds)
	{
		$fieldsIds = (array)$fieldsIds;
		if (!count($fieldsIds)) {
			return false;
		}

		$categoryField = $this->getFieldByAlias('categoryId');
		$fieldIdField = $this->getFieldByAlias('fieldId');
		$sortOrderField = $this->getFieldByAlias('sortOrder');
/*
		$existsFields = $this->getSelectedFields(
										array('fieldId'),
										array(
											$this->getFullFieldName('categoryId') . ' = ?',
											array($categoryId)
										)
								);
*/
		$sortOrder = $this->getMaxSortOrder($categoryId);
		$sortOrder = (empty($sortOrder) ? 1000 : $sortOrder);
		
		$this->getAdapter()->beginTransaction();
		//$this->clearbyCategoryId($categoryId);
		foreach ($fieldsIds as $fieldId) {
/*
			if (in_array($fieldId, $existsFields)) {
				continue;
			}
*/
			$row = $this->createRow(
							array(
								$categoryField => $categoryId,
								$fieldIdField => $fieldId,
								$sortOrderField => ++$sortOrder
							)
						);
			try {
				$row->save();
			} catch (PTA_Exception $e) {}
		}
		return $this->getAdapter()->commit();
	}
	
	/**
	 * Remove cztegory fields
	 *
	 * @param int $categoryId
	 * @param array $fieldsIds
	 * @return boolean
	 */
	public function delCategoryFields($categoryId, $fieldsIds = array())
	{
		if (empty($fieldsIds)) {
			return $this->clearbyCategoryId($categoryId);
		} else {
			return $this->clearByFields(
				array(
					'categoryId' => (int)$categoryId,
					'fieldId' => $fieldsIds
				)
			);
		}
	}
	
	/**
	 * Get currrent maximum order position
	 *
	 * @param int $categoryId
	 * @return int
	 */
	public function getMaxSortOrder($categoryId)
	{
		if (empty($categoryId)) {
			return 1000;
		}

		return $this->getDefaultAdapter()->fetchOne(
			$this->select()->from(
				$this->getTableName(), 
				array('max' => 'MAX(' . $this->getFieldByAlias('sortOrder') . ')')
			)->where(
				$this->getFieldByAlias('categoryId') . ' = ?', $categoryId
			)
		);
	}
	
	public function setFieldsSortOrder($fields)
	{
		if (empty($fields)) {
			return false;
		}

		$fieldIdField = $this->getPrimary();
		$fieldOrderField = $this->getFieldByAlias('sortOrder');

		$this->getAdapter()->beginTransaction();
		foreach ($fields as $fieldId => $sortOrder) {
			$this->update(
				array(
					$fieldOrderField => intval($sortOrder)
				),
				$fieldIdField . ' = ' . intval($fieldId)
			);
		}
		return $this->getAdapter()->commit();
	}
}
