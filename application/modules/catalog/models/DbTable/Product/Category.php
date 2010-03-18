<?php
/**
 * Catalog Product Category Database Table
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
 * @version    $Id$
 */

class Catalog_Model_DbTable_Product_Category extends KIT_Db_Table_Tree_Abstract
{
	protected $_name = 'CATALOG_PRODUCTCATEGORIES';
	protected $_primary = 'PRODUCTCATEGORIES_ID';

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
				array('pc' => $this->_name),
				$this->getFields(false)
			)
		);
	}

	/**
	 * Get Product Default Category
	 *
	 * @param int $productId
	 * @param boolean $fullRecord
	 * @return int|Zend_Db_Table_Row_Abstract
	 */
	public function getDefaultCategory($productId, $fullRecord = false)
	{
		$productId = (int)$productId;
		if (empty($productId)) {
			return false;
		}

		$select = $this->select()->from(
			$this->_name,
			array('PRODUCTCATEGORIES_CATEGORYID')
		);
		$select->where('PRODUCTCATEGORIES_PRODUCTID = ' . $productId);
		$select->where('PRODUCTCATEGORIES_ISDEFAULT = 1');

		if ($fullRecord) {
			$select->columns($this->getFields(false));
			return $this->fetchRow($select);
		} else {
			return $this->getAdapter()->fetchOne($select->limit(1));
		}
	}
	
	/**
	 * Set Product Default Category
	 *
	 * @param int $productId
	 * @param int $categoryId
	 * @return boolean
	 */
	public function setDefaultCategory($productId, $categoryId)
	{
		$productId  = (int)$productId;
		$categoryId = (int)$categoryId;
		if (empty($productId) || empty($categoryId)) {
			return false;
		}
		return $this->update(
			array('PRODUCTCATEGORIES_CATEGORYID' => $categoryId),
			array(
				'PRODUCTCATEGORIES_PRODUCTID = ' . $productId,
				'PRODUCTCATEGORIES_ISDEFAULT = 1'
			)
		);
	}
	
	/**
	 * Get Category Fields
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getCategoryFields($categoryId)
	{
		$categoryId = intval($categoryId);
		if (empty($categoryId)) {
			return array();
		}

		$fieldsTable         = self::get('Catalog_Model_DbTable_Field');
		$groupFieldsTable    = self::get('Catalog_Model_DbTable_Field_Group_Field');
		$categoryGroupsTable = self::get('Catalog_Model_DbTable_Category_Group');

		$select = $this->select()->from(
			array('fields' => $fieldsTable->getTableName()),
			$fieldsTable->getFields(false)
		);
		$select->setIntegrityCheck(false);

		$select->join(
			array('gf' => $groupFieldsTable->getTableName()),
			'fields.' . $fieldsTable->getPrimary()
			. ' = gf.' . $groupFieldsTable->getFieldByAlias('fieldId'),
			array($groupFieldsTable->getPrimary())
		);

		$select->join(
			array('cg' => $categoryGroupsTable->getTableName()),
			'gf.' . $groupFieldsTable->getFieldByAlias('categoryGroupId')
			. ' = cg.' . $categoryGroupsTable->getPrimary(),
			array()
		);

		$select->where(
			'cg.' . $categoryGroupsTable->getFieldByAlias('categoryId')
			. ' = ' . $categoryId
		);
		
		$select->order(
			array(
				'cg.' . $categoryGroupsTable->getFieldByAlias('sortOrder'),
				'gf.' . $groupFieldsTable->getFieldByAlias('sortOrder')
			)
		);
		return $this->fetchAll($select);
	}
}
