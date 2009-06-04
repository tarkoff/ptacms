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

		
		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$select->join(
			array('cft' => $categoryFieldTable->getTableName()),
			'vt.' . $this->getFieldByAlias('fieldId') 
			. ' = cft.' . $categoryFieldTable->getPrimary(),
			array()
		);

		$fieldTable = PTA_DB_Table::get('Catalog_Field');
		$select->join(
					array('ft' => $fieldTable->getTableName()),
					'cft.' . $categoryFieldTable->getFieldByAlias('fieldId') . ' = ft.' . $fieldTable->getPrimary()
				);

		$fieldValueTable = PTA_DB_Table::get('Catalog_Field_Value');
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
		$select->order('cft.' . $categoryFieldTable->getFieldByAlias('sortOrder'));
		$select->setIntegrityCheck(false);
		return $this->fetchAll($select)->toArray();
	}
}
