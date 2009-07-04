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

class PTA_Catalog_FieldGroup_Field_Table extends PTA_DB_Table 
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
}