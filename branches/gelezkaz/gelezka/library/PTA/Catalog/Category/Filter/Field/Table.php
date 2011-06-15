<?php
/**
 * Catalog Category Filter Field Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 71 2009-07-04 10:57:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category_Filter_Field_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_FILTERFIELDS';
	protected $_primary = 'FILTERFIELD_ID';

	public function getCategoryFields($categoryIds = array(), $catFields = true)
	{
		if (empty($categoryIds)) {
			return array();
		}

		$categoryIds = array_map('intval', (array)$categoryIds);

		$fieldsTable = self::get('Catalog_Field');
		$categoryFieldsTable = self::get('Catalog_Category_Field');

		$select = $fieldsTable->select()
			->from(
				array('fields' => $fieldsTable->getTableName()),
				array($fieldsTable->getPrimary(), $fieldsTable->getFieldByAlias('title'))
			);
		$select->setIntegrityCheck(false);

		$select->join(
			array('catFields' => $categoryFieldsTable->getTableName()),
			'catFields.' . $categoryFieldsTable->getFieldByAlias('fieldId')
			. ' = fields.' . $fieldsTable->getPrimary(),
			array($categoryFieldsTable->getPrimary())
		);

		if ($catFields) {
			$select->join(
				array('filterFields' => $this->getTableName()),
				'catFields.' . $categoryFieldsTable->getPrimary()
				. ' = filterFields.' . $this->getFieldByAlias('categoryFieldId'),
				array()
			);

			$select->where('filterFields.' . $this->getFieldByAlias('categoryId') . ' in (?)', $categoryIds);
		} else {
			$select->joinLeft(
				array('filterFields' => $this->getTableName()),
				'catFields.' . $categoryFieldsTable->getPrimary()
				. ' = filterFields.' . $this->getFieldByAlias('categoryFieldId'),
				array()
			);

			$select->where('catFields.' . $categoryFieldsTable->getFieldByAlias('categoryId') . ' in (?)', $categoryIds);
			$select->where('filterFields.' . $this->getFieldByAlias('categoryFieldId') . ' is null');
		}

		return $this->fetchAll($select)->toArray();
	}

	public function setCategoryFields($categoryId, $fieldIds = array())
	{
		if (empty($categoryId) || empty($fieldIds)) {
			return false;
		}

		$categoryField = $this->getFieldByAlias('categoryId');
		$fieldIdField = $this->getFieldByAlias('categoryFieldId');

		$categoryId = (int)$categoryId;
		$this->getAdapter()->beginTransaction();
		foreach ($fieldIds as $fieldId) {
			$row = $this->createRow(
				array(
					$categoryField => $categoryId,
					$fieldIdField => $fieldId,
				)
			);
			try {
				$row->save();
			} catch (PTA_Exception $e) {}
		}

		return $this->getAdapter()->commit();
	}
	
	public function clearCategoryFields($categoryIds = array())
	{
		if (empty($categoryIds)) {
			return array();
		}
		return $this->clearByFields(
			array('categoryId' => $categoryIds)
		);
	}
}
