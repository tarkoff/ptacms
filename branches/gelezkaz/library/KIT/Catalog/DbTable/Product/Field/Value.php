<?php
/**
 * Catalog Product Field Value Database Table
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

class KIT_Catalog_DbTable_Product_Field_Value extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_PRODUCTVALUES';
	protected $_primary = 'PRODUCTVALUES_ID';

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
				array('pv' => $this->_name),
				$this->getFields(false)
			)
		);
	}

	/**
	 * Get product fields with values
	 *
	 * @param $productId
	 * @return unknown_type
	 */
	public function getProductValues($productId)
	{
		$productId = (int)$productId;
		if (empty($productId)) {
			return array();
		}

		$fieldValuesTable = self::get('KIT_Catalog_DbTable_Field_Value');

		$select = $fieldValuesTable->select()->from(
			array('fv' => $fieldValuesTable->getTableName()),
			$fieldValuesTable->getFields(false)
		);
		$select->setIntegrityCheck(false);

		$select->join(
			array('pv' => $this->getTableName()),
			'fv.' . $fieldValuesTable->getPrimary()
			. ' = pv.' . $this->getFieldByAlias('valueId'),
			$this->getFields(false)
		);

		$select->where('pv.' . $this->getFieldByAlias('productId') . '=' . intval($productId));

		return $this->fetchAll($select);
	}

	/**
	 * Delete all product values
	 *
	 * @param int $productId
	 * @return boolean
	 */
	public function clearProductValues($productId)
	{
		return $this->delete($this->getFieldByAlias('productId') . ' = ' . intval($productId));
	}
}
