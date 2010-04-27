<?php
/**
 * Catalog Category Field Group Database Table
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
 * @version    $Id: Group.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class KIT_Catalog_DbTable_Category_Group extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_CATEGORYGROUPS';
	protected $_primary = 'CATEGORYGROUPS_ID';

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
		$groupsTable = self::get('KIT_Catalog_DbTable_Field_Group');

		$select = $this->getAdapter()->select()->from(
			array('cg' => $this->_name),
			array(
				'CATEGORYGROUPS_ID',
				'CATEGORYGROUPS_CATEGORY' => 'cats.' . $categoryTable->getFieldByAlias('title'),
				'CATEGORYGROUPS_GROUP'    => 'groups.' . $groupsTable->getFieldByAlias('title'),
				'CATEGORYGROUPS_SORTORDER'
			)
		);

		$select->join(
			array('cats' => $categoryTable->getTableName()),
			'cg.CATEGORYGROUPS_CATEGORYID = cats.' . $categoryTable->getPrimary(),
			array()
		);

		$select->join(
			array('groups' => $groupsTable->getTableName()),
			'groups.' . $groupsTable->getPrimary() . ' = cg.CATEGORYGROUPS_GROUPID',
			array('CATEGORYGROUPS_GROUP' => $groupsTable->getFieldByAlias('title'))
		);

		$this->setViewSelect($select);
	}

	/**
	 * Get groups allowed for adding to category
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getFreeGroups($categoryId)
	{
		$categoryId = (int)$categoryId;
		if (empty($categoryId)) {
			return array();
		}
		$groupsTable = self::get('KIT_Catalog_DbTable_Field_Group');

		$select = $groupsTable->select()
							  ->from(
							  		array('groups' => $groupsTable->getTableName()),
							  		$groupsTable->getFields(false)
							  );
		$select->setIntegrityCheck(false);

		$select->joinLeft(
			array('cg' => $this->getTableName()),
			'groups.' . $groupsTable->getPrimary() . ' = cg.CATEGORYGROUPS_GROUPID',
			array('CATEGORYGROUPS_ID', 'CATEGORYGROUPS_SORTORDER')
		);

		$select->where('cg.CATEGORYGROUPS_ID IS NULL');
		$select->orWhere('cg.CATEGORYGROUPS_CATEGORYID <> ' . $categoryId);
		$select->order('cg.CATEGORYGROUPS_SORTORDER');
		
		return $this->fetchAll($select);
	}

	/**
	 * Get category groups
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getCategoryGroups($categoryId)
	{
		$categoryId = (int)$categoryId;
		if (empty($categoryId)) {
			return array();
		}
		$groupsTable = self::get('KIT_Catalog_DbTable_Field_Group');

		$select = $groupsTable->select()
							  ->from(
							  		array('groups' => $groupsTable->getTableName()),
							  		$groupsTable->getFields(false)
							  );
		$select->setIntegrityCheck(false);

		$select->join(
			array('cg' => $this->getTableName()),
			'groups.' . $groupsTable->getPrimary() . ' = cg.CATEGORYGROUPS_GROUPID',
			array('CATEGORYGROUPS_ID', 'CATEGORYGROUPS_SORTORDER')
		);

		$select->where('cg.CATEGORYGROUPS_CATEGORYID = ' . $categoryId);
		$select->order('cg.CATEGORYGROUPS_SORTORDER');

		return $this->fetchAll($select);
	}

	/**
	 * Save category groups
	 *
	 * @param int $categoryId
	 * @param mixed $groups
	 * @return boolean
	 */
	public function setCategoryGroups($categoryId, $groups)
	{
		$categoryId = (int)$categoryId;
		$groups = (array)$groups;

		if (empty($categoryId)) {
			return false;
		}

		$sql = 'INSERT IGNORE INTO '
				. $this->getTableName()
				. ' (CATEGORYGROUPS_CATEGORYID, CATEGORYGROUPS_GROUPID, CATEGORYGROUPS_SORTORDER) VALUES ';

		$sqlParts = array();
		foreach ($groups as $groupId => $groupOrder) {
			$sqlParts[] = '('. intval($categoryId) . ', ' . $groupId . ', ' . intval($groupOrder) . ')';
		}

		$this->delete('CATEGORYGROUPS_CATEGORYID = ' . $categoryId);
		if (!empty($sqlParts)) {
			return $this->getAdapter()->query($sql . implode(', ', $sqlParts));
		}
		return true;
	}
}
