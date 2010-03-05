<?php
/**
 * Catalog Category Field Database Table
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
 * @version    $Id: Menu.php 278 2010-02-27 18:36:32Z TPavuk $
 */

class Catalog_Model_DbTable_Category_Field extends KIT_Db_Table_Tree_Abstract
{
	protected $_name = 'CATALOG_CATEGORYFIELDS';
	protected $_primary = 'CATEGORYFIELDS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$this->setViewSelect(
			$this->getAdapter()->select()->from(
				array('cf' => $this->_name),
				$this->getFields(false)
			)
		);
	}

	/**
	 * Get fields allowed for adding to category
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getFreeFields($categoryId)
	{
		$categoryId = (int)$categoryId;
		if (empty($categoryId)) {
			return array();
		}
		$fieldsTable = self::get('Catalog_Model_DbTable_Field');

		$select = $fieldsTable->select()
							  ->from(
							  		array('fields' => $fieldsTable->getTableName()),
							  		$fieldsTable->getFields(false)
							  );
		$select->setIntegrityCheck(false);

		$select->joinLeft(
			array('cf' => $this->getTableName()),
			'fields.' . $fieldsTable->getPrimary() . ' = cf.CATEGORYFIELDS_FIELDID',
			array('CATEGORYFIELDS_ID', 'CATEGORYFIELDS_SORTORDER')
		);

		$select->where('cf.CATEGORYFIELDS_ID IS NULL');
		$select->orWhere('cf.CATEGORYFIELDS_CATEGORYID <> ' . $categoryId);
		$select->order('cf.CATEGORYFIELDS_SORTORDER');
		
		return $this->fetchAll($select);
	}

	/**
	 * Get category fields
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getCategoryFields($categoryId)
	{
		$categoryId = (int)$categoryId;
		if (empty($categoryId)) {
			return array();
		}
		$fieldsTable = self::get('Catalog_Model_DbTable_Field');

		$select = $fieldsTable->select()
							  ->from(
							  		array('fields' => $fieldsTable->getTableName()),
							  		$fieldsTable->getFields(false)
							  );
		$select->setIntegrityCheck(false);

		$select->join(
			array('cf' => $this->getTableName()),
			'fields.' . $fieldsTable->getPrimary() . ' = cf.CATEGORYFIELDS_FIELDID',
			array('CATEGORYFIELDS_ID', 'CATEGORYFIELDS_SORTORDER')
		);

		$select->where('cf.CATEGORYFIELDS_CATEGORYID = ' . $categoryId);
		$select->order('cf.CATEGORYFIELDS_SORTORDER');

		return $this->fetchAll($select);
	}

	/**
	 * Save category fields
	 *
	 * @param int $categoryId
	 * @param mixed $fields
	 * @return boolean
	 */
	public function setCategoryFields($categoryId, $fields)
	{
		$categoryId = (int)$categoryId;
		$fields = (array)$fields;

		if (empty($categoryId)) {
			return false;
		}

		$sql = 'INSERT IGNORE INTO '
				. $this->getTableName()
				. ' (CATEGORYFIELDS_CATEGORYID, CATEGORYFIELDS_FIELDID, CATEGORYFIELDS_SORTORDER) VALUES ';

		$sqlParts = array();
		foreach ($fields as $fieldId => $fieldOrder) {
			$sqlParts[] = '('. intval($categoryId) . ', ' . $fieldId . ', ' . intval($fieldOrder) . ')';
		}

		$this->delete('CATEGORYFIELDS_CATEGORYID = ' . $categoryId);
		if (!empty($sqlParts)) {
			return $this->getAdapter()->query($sql . implode(', ', $sqlParts));
		}
		return false;
	}
}
