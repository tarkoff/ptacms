<?php
/**
 * Field Group Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_FieldGroup_Table extends PTA_DB_Table 
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
	
	private function _getGroupFields($groupId, $categoryId = null, $equal = true)
	{
		if (empty($groupId)) {
			return array();
		}

		$groupId = (int)$groupId;
		$categoryId = (int)$categoryId;

		if (empty($categoryId)) {
			$group = $this->find($groupId)->toArray();
			$categoryId = (int)$group[$this->getFieldByAlias('categoryId')];
		}

		$fieldsTable = self::get('Catalog_Field');
		$catFieldsTable = self::get('Catalog_CategoryField');
		$groupFieldsTable = self::get('Catalog_FieldGroup_Field');
		
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
			$select->where('groupFields.' . $groupFieldsTable->getFieldByAlias('groupId') . ' <> ?', $groupId);
			$select->orWhere('groupFields.' . $groupFieldsTable->getFieldByAlias('groupId') . ' is null');
		}

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
		
		$select = $this->select()->where(
			$this->getFieldByAlias('categoryId') . ' = ?', intval($categoryId)
		);
		
		return $this->fetchAll($select)->toArray();
	}
}
