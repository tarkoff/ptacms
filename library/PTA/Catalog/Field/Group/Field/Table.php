<?php
/**
 * Field Group Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 71 2009-07-04 10:57:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Group_Field_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_FIELDGROUPFIELDS';
	protected $_primary = 'FIELDGROUPFIELDS_ID';
	

	public function removeGroupFields($groupId, $fieldsIds = array())
	{
		if (empty($groupId)) {
			return false;
		}
		
		$where = $this->getFieldByAlias('groupId') . ' = ' . intval($groupId);
		if (!empty($fieldsIds)) {
			$where .= ' and ' . $this->getAdapter()->quoteInto(
				$this->getFieldByAlias('fieldId') . ' in (?)', $fieldsIds
			);
		}

		return $this->delete($where);
	}

	/**
	 * Add Fields To Categories Group
	 *
	 * @param int $groupId
	 * @param array $fieldsIds
	 * @return boolean
	 */
	public function addGroupFields($groupId, $fieldsIds)
	{
		if (empty($groupId) || empty($fieldsIds)) {
			return false;
		}
		
		$groupId = intval($groupId);
		$fieldsIds = (array)$fieldsIds;
		
		$groupIdField = $this->getFieldByAlias('groupId');
		$fieldIdField = $this->getFieldByAlias('fieldId');
		
		$this->getAdapter()->beginTransaction();
		foreach ($fieldsIds as $fieldId) {
			$this->insert(
				array(
					$groupIdField => $groupId,
					$fieldIdField => intval($fieldId)
				)
			);
		}
		return $this->getAdapter()->commit();
	}
	
	/**
	 * Get Group Fields
	 *
	 * @param int|array $groupId
	 * @return array
	 */
	public function getGroupFields($groupId)
	{
		if (empty($groupId)) {
			return array();
		}

		$select = $this->select();

		if (is_array($groupId)) {
			$select->where($this->getFieldByAlias('groupId') . ' in (?)', $groupId);
		} else {
			$select->where($this->getFieldByAlias('groupId') . ' = ?', intval($groupId));
		}

		return $this->fetchAll($select)->toArray();
	}
}
