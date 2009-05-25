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

class PTA_Catalog_Value_Table extends PTA_DB_Table 
{
	/**
	 * The default table name
	 */
	protected $_name = 'PRODUCTSVALUES';
	protected $_primary = 'PRODUCTSVALUES_ID';
	protected $_sequence = true;

	public function getValuesByProductId($productId)
	{
		$select = $this->select()->from(array('vt' => $this->getTableName()));
		$select->where(
					'vt.' . $this->getFieldByAlias('productId') . ' = ?',
					(int)$productId
				);

		
		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$select->join(
					array('cft' => $categoryFieldTable->getTableName()),
					'vt.' . $this->getFieldByAlias('fieldId') . ' = cft.' . $categoryFieldTable->getPrimary(),
					array()
				);

		$fieldTable = PTA_DB_Table::get('Catalog_Field');
		$select->join(
					array('ft' => $fieldTable->getTableName()),
					'cft.' . $categoryFieldTable->getFieldByAlias('fieldId') . ' = ft.' . $fieldTable->getPrimary()
				);
		$select->order('cft.' . $categoryFieldTable->getFieldByAlias('sortOrder'));
		$select->setIntegrityCheck(false);
		return $this->fetchAll($select)->toArray();
	}
}
