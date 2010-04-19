<?php
/**
 * Catalog Field Value Database Table
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

class Catalog_Model_DbTable_Field_Value extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_FIELDSVALUES';
	protected $_primary = 'FIELDSVALUES_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$fieldsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field');
		$select = $this->getAdapter()->select()->from(
			array('fv' => $this->_name),
			array(
				'FIELDSVALUES_ID',
				'fields.' . $fieldsTable->getFieldByAlias('title') . ' as FIELDSVALUES_FIELDID',
				'FIELDSVALUES_VALUE'
			)
		);
		
		$select->join(
			array('fields' => $fieldsTable->getTableName()),
			'fv.FIELDSVALUES_FIELDID = fields.' . $fieldsTable->getPrimary(),
			array()
		);
		
		$this->setViewSelect($select);
	}

	/**
	 * Get All Field Values
	 *
	 * @param int|array $fieldIds
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getFieldValues($fieldIds)
	{
		$fieldIds = (array)$fieldIds;
		if (empty($fieldIds)) {
			return array();
		}
		
		$fieldIds = array_map('intval', $fieldIds);
		
		return $this->fetchAll(
			$this->select()->where('FIELDSVALUES_FIELDID in (?)', $fieldIds)
		);
	}
}
