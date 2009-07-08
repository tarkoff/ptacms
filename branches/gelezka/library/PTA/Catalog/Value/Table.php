<?php
/**
 * Catalog Product Value Table
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Value_Table extends PTA_DB_Table 
{
	/**
	 * The default table name
	 */
	protected $_name = 'CATALOG_PRODUCTSVALUES';
	protected $_primary = 'PRODUCTSVALUES_ID';

	public function getValuesByProductId($productId, $allValues = true)
	{
		$categoryFieldTable = PTA_DB_Table::get('Catalog_Category_Field');
		$fieldTable = PTA_DB_Table::get('Catalog_Field');
		$fieldValueTable = PTA_DB_Table::get('Catalog_Field_Value');
		$fieldGroupTable = PTA_DB_Table::get('Catalog_Field_Group_Field');

		$select = $this->select()->from(
			array('vt' => $this->getTableName()),
			array(
				$this->getFieldByAlias('productId'),
				$this->getFieldByAlias('fieldId'),
				$this->getFieldByAlias('valueId')
			)
		);
		$select->where(
			'vt.' . $this->getFieldByAlias('productId') . ' = ?',
			(int)$productId
		);

		$select->join(
			array('cft' => $categoryFieldTable->getTableName()),
			'vt.' . $this->getFieldByAlias('fieldId') 
			. ' = cft.' . $categoryFieldTable->getPrimary(),
			array()
		);

		$select->join(
					array('ft' => $fieldTable->getTableName()),
					'cft.' . $categoryFieldTable->getFieldByAlias('fieldId') . ' = ft.' . $fieldTable->getPrimary()
				);

		if ($allValues) {
			$select->join(
				array('fvt' => $fieldValueTable->getTableName()),
				'cft.' . $categoryFieldTable->getFieldByAlias('fieldId') 
				. ' = fvt.' . $fieldValueTable->getFieldByAlias('fieldId'),
				array($fieldValueTable->getFieldByAlias('value'))
			);
		} else {
			$select->join(
				array('fvt' => $fieldValueTable->getTableName()),
				'vt.' .  $this->getFieldByAlias('valueId') 
				. ' = fvt.' . $fieldValueTable->getPrimary(),
				array($fieldValueTable->getFieldByAlias('value'))
			);
		}
		
		$select->joinLeft(
			array('gf' => $fieldGroupTable->getTableName()),
			'gf.' . $fieldGroupTable->getFieldByAlias('fieldId')
			. ' = cft.' . $categoryFieldTable->getPrimary(),
			array($fieldGroupTable->getFieldByAlias('groupId'))
		);

		$select->order('cft.' . $categoryFieldTable->getFieldByAlias('sortOrder'));
		$select->setIntegrityCheck(false);

		return $this->fetchAll($select)->toArray();
	}
}
