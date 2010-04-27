<?php
/**
 * Catalog Field Group Field Database Table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Field.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class KIT_Catalog_DbTable_Field_Group_Field extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_GROUPFIELDS';
	protected $_primary = 'GROUPFIELDS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$categoryTable = self::get('KIT_Catalog_DbTable_Category');
		$categoryGroupTable = self::get('KIT_Catalog_DbTable_Category_Group');
		$fieldsTable = self::get('KIT_Catalog_DbTable_Field');
		$fieldGroupsTable = self::get('KIT_Catalog_DbTable_Field_Group');
		
		$select = $this->getAdapter()
					   ->select()
					   ->from(
							array('fields' => $fieldsTable->getTableName()),
							array(
								'gf.GROUPFIELDS_ID',
								'CATEGORY_TITLE' => 'cat.' . $categoryTable->getFieldByAlias('title'),
								'GROUP_TITLE'    => 'fg.' . $fieldGroupsTable->getFieldByAlias('title'),
								'FIELD_TITLE'    => 'fields.' . $fieldsTable->getFieldByAlias('title'),
								'gf.GROUPFIELDS_SORTORDER',
								'gf.GROUPFIELDS_INFILTER'
							)
						);

		$select->join(
			array('gf' => $this->getTableName()),
			'fields.' . $fieldsTable->getPrimary() . ' = gf.GROUPFIELDS_FIELDID',
			array()
		);

		$select->join(
			array('cg' => $categoryGroupTable->getTableName()),
			'cg.' . $categoryGroupTable->getPrimary() . ' = gf.GROUPFIELDS_CATEGORYGROUPID',
			array()
		);

		$select->join(
			array('fg' => $fieldGroupsTable->getTableName()),
			'fg.' . $fieldGroupsTable->getPrimary() . ' = cg.' . $categoryGroupTable->getFieldByAlias('groupId'),
			array()
		);

		$select->join(
			array('cat' => $categoryTable->getTableName()),
			'cat.' . $categoryTable->getPrimary() . ' = cg.' . $categoryGroupTable->getFieldByAlias('categoryId'),
			array()
		);
		$this->setViewSelect($select);
	}

	/**
	 * Get fields allowed for adding to group
	 *
	 * @param int $groupId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getFreeFields($groupId)
	{
		$groupId = (int)$groupId;
		if (empty($groupId)) {
			return array();
		}
		$fieldsTable = self::get('KIT_Catalog_DbTable_Field');

		$select = $fieldsTable->select()
							  ->from(
							  		array('fields' => $fieldsTable->getTableName()),
							  		$fieldsTable->getFields(false)
							  );
		$select->setIntegrityCheck(false);

		$select->joinLeft(
			array('gf' => $this->getTableName()),
			'fields.' . $fieldsTable->getPrimary() . ' = gf.GROUPFIELDS_FIELDID',
			array('GROUPFIELDS_ID', 'GROUPFIELDS_SORTORDER')
		);

		$select->where('gf.GROUPFIELDS_ID IS NULL');
		$select->orWhere('gf.GROUPFIELDS_CATEGORYGROUPID <> ' . $groupId);
		$select->order('gf.GROUPFIELDS_SORTORDER');

		return $this->fetchAll($select);
	}

	/**
	 * Get group fields
	 *
	 * @param int|array $groupId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getGroupFields($groupId)
	{
		if (empty($groupId)) {
			return array();
		}

		$groupId     = array_map('intval', (array)$groupId);
		$fieldsTable = self::get('KIT_Catalog_DbTable_Field');

		$select = $fieldsTable->select()
							  ->from(
							  		array('fields' => $fieldsTable->getTableName()),
							  		$fieldsTable->getFields(false)
							  );
		$select->setIntegrityCheck(false);

		$select->join(
			array('gf' => $this->getTableName()),
			'fields.' . $fieldsTable->getPrimary() . ' = gf.GROUPFIELDS_FIELDID',
			array('GROUPFIELDS_ID', 'GROUPFIELDS_CATEGORYGROUPID', 'GROUPFIELDS_SORTORDER')
		);

		$select->where('gf.GROUPFIELDS_CATEGORYGROUPID in (?)', $groupId);
		$select->order('gf.GROUPFIELDS_SORTORDER');

		return $this->fetchAll($select);
	}

	/**
	 * Save group fields
	 *
	 * @param int $groupId
	 * @param mixed $fields
	 * @return boolean
	 */
	public function setGroupFields($groupId, $fields)
	{
		$groupId = (int)$groupId;
		$fields = (array)$fields;

		if (empty($groupId)) {
			return false;
		}

		$sql = 'INSERT IGNORE INTO '
				. $this->getTableName()
				. ' (GROUPFIELDS_CATEGORYGROUPID, GROUPFIELDS_FIELDID, GROUPFIELDS_SORTORDER) VALUES ';

		$sqlParts = array();
		$fieldsIds = array();
		foreach ($fields as $fieldId => $fieldOrder) {
			$fieldId = intval($fieldId);
			$sqlParts[] = '('. intval($groupId) . ', ' . $fieldId . ', ' . intval($fieldOrder) . ')';
			$fieldsIds[] = $fieldId;
		}

		if (!empty($sqlParts)) {
			$this->delete(
				'GROUPFIELDS_CATEGORYGROUPID = ' . $groupId
				. ' AND GROUPFIELDS_FIELDID NOT IN (' . implode(',', $fieldsIds) . ')');
			return $this->getAdapter()->query($sql . implode(', ', $sqlParts));
		}
		return true;
	}
}
