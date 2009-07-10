<?php
/**
 * Field Group Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 77 2009-07-04 19:17:54Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Group_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_FIELDSGROUPS';
	protected $_primary = 'FIELDSGROUPS_ID';

	/**
	 * Get Category Group Fields
	 *
	 * @param int $groupId
	 * @param int $categoryId
	 * @return array
	 */
	public function getGroupFields($groupId, $categoryId = null)
	{
		return $this->_getGroupFields($groupId, $categoryId, true);
	}

	private function _getGroupFields($groupId, $categoryId, $equal = true)
	{
		if (empty($groupId) || empty($categoryId)) {
			return array();
		}

		$groupId = (int)$groupId;
		$categoryId = (int)$categoryId;

		$fieldsTable = self::get('Catalog_Field');
		$catFieldsTable = self::get('Catalog_Category_Field');
		$groupFieldsTable = self::get('Catalog_Field_Group_Field');

		$select = $this->select()->from(
			array('fields' => $fieldsTable->getTableName()),
			array($fieldsTable->getFieldByAlias('title'))
		);

		$select->setIntegrityCheck(false);

		$select->join(
			array('catFields' => $catFieldsTable->getTableName()),
			'fields.' . $fieldsTable->getPrimary() 
			. ' = catFields.' . $catFieldsTable->getFieldByAlias('fieldId'),
			array($catFieldsTable->getPrimary())
		);

		$select->joinLeft(
			array('groupFields' => $groupFieldsTable->getTableName()),
			'groupFields.' . $groupFieldsTable->getFieldByAlias('fieldId')
			. ' = catFields.' . $catFieldsTable->getPrimary(),
			array()
		);

		if ($equal) {
			$select->where('groupFields.' . $groupFieldsTable->getFieldByAlias('groupId') . ' = ?', $groupId);
		} else {
			$groupCategoryIdField = $this->getFieldByAlias('categoryId');
			$select->joinLeft(
				array('catGroups' => $this->getTableName()),
				'catFields.' . $catFieldsTable->getFieldByAlias('categoryId')
				. ' = catGroups.' . $groupCategoryIdField,
				array()
			);

			$catTable = self::get('Catalog_Category');
			$catIdField = $catTable->getPrimary();

			$parentCats = $catTable->getRootCategory($categoryId);
			$resCats = array($categoryId);
			foreach ($parentCats as $category) {
				$resCats[] = $category[$catIdField];
			}

			$select->where('catGroups.' . $groupCategoryIdField . ' in (?)', $resCats);
			$select->where('(groupFields.' . $groupFieldsTable->getFieldByAlias('groupId') . ' <> ?', $groupId);
			$select->orWhere('groupFields.' . $groupFieldsTable->getFieldByAlias('groupId') . ' is null)');
		}

		$select->group('fields.' . $fieldsTable->getPrimary());

		return $this->fetchAll($select)->toArray();
	}

	/**
	 * Get Not Category Group Fields
	 *
	 * @param int $groupId
	 * @param int $categoryId
	 * @return array
	 */
	public function getNotGroupFields($groupId, $categoryId = null)
	{
		return $this->_getGroupFields($groupId, $categoryId, false);
	}

	public function getCategoryGroups($categoryId)
	{
		if (empty($categoryId)) {
			return array();
		}

		$select = $this->select();
		$select->where(
			$this->getFieldByAlias('categoryId') . ' = ?', intval($categoryId)
		);
		$select->order($this->getFieldByAlias('sortOrder'));

		return $this->fetchAll($select)->toArray();
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
			return 1;
		}

		$order = $this->getAdapter()->fetchOne(
			$this->select()->from(
				$this->getTableName(), 
				array('max' => 'MAX(' . $this->getFieldByAlias('sortOrder') . ')')
			)->where(
				$this->getFieldByAlias('categoryId') . ' = ?', $categoryId
			)
		);
		
		return (empty($order) ? 1 : intval($order));
	}

	/**
	 * Set Sort Order For Category Groups
	 *
	 * @param array $groups
	 * @return boolean
	 */
	public function setFieldsSortOrder($groups)
	{
		if (empty($groups)) {
			return false;
		}

		$groupIdField = $this->getPrimary();
		$groupOrderField = $this->getFieldByAlias('sortOrder');

		$this->getAdapter()->beginTransaction();
		foreach ($groups as $groupId => $sortOrder) {
			$this->update(
				array(
					$groupOrderField => intval($sortOrder)
				),
				$groupIdField . ' = ' . intval($groupId)
			);
		}
		return $this->getAdapter()->commit();
	}
}
