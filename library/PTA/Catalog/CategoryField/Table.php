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

class PTA_Catalog_CategoryField_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATEGORIESFIELDS';
	protected $_primary = 'CATEGORIESFIELDS_ID';
	protected $_sequence = true;
	protected static $_fieldsCache = array();
	
	public function getFieldsByCategory($categoryId, $equal = true, $parentsFieldsToo = false)
	{
		if (empty($categoryId)) {
			return null;
		}

		$cachePrefix = $categoryId . '_' . intval($equal) . '_' . intval($parentsFieldsToo);
		if (isset(self::$_fieldsCache[$cachePrefix])) {
			return self::$_fieldsCache[$cachePrefix];
		}

		$fieldsIds = array();
		$fieldIdField = $this->getFieldByAlias('fieldId');
		$categoryIdField = $this->getFieldByAlias('categoryId');
		do {
			$category = new PTA_Catalog_Category("Category_{$categoryId}");
			$category->loadById($categoryId);

			if ($categoryId) {
				$select = $this->select()->where("$categoryIdField = ?", $categoryId);
				$fields = $this->fetchAll($select)->toArray();
				foreach ($fields as $field) {
					$fieldsIds[] = $field[$fieldIdField];
				}
			}
			
			if ($parentsFieldsToo) {
				$categoryId = $category->getParentId();
			} else {
				$categoryId = false;
			}
		} while ($categoryId);

		if (!empty($fieldsIds)) {
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
			$select->where('categoriesFields.' . $this->getFieldByAlias('fieldId') . ' in (?)', $fieldsIds);
		} else {
			$select->joinLeft(
						array('categoriesFields' => $this->getTableName()),
						'categoriesFields.' . $this->getFieldByAlias('fieldId') . ' = fields.' . $fieldsTable->getPrimary()
						/*array_values($this->getFields())*/
					);

			$select->where('categoriesFields.' . $this->getFieldByAlias('fieldId') . ' not in (?)', $fieldsIds);
		}

		$select->orWhere('categoriesFields.' . $this->getFieldByAlias('categoryId') . ' is null');
		$select->order('categoriesFields.' . $this->getFieldByAlias('sortOrder'));
		$select->setIntegrityCheck(false);

		return $this->fetchAll($select)->toArray();
	}

	public function getFieldsByNotCategory($categoryId)
	{
		return $this->getFieldsByCategory($categoryId, false);
	}

	public function clearbyCategoryId($categoryId)
	{
		$fields = array(
					array(
						'field' => $this->getFieldByAlias('categoryId'),
						'value' => (int)$categoryId
					)
					);
		return $this->clearByFields($fields);
	}
}
