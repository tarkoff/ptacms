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
		$select = $this->select()->from(array('valt' => $this->getTableName()));
		$select->where(
					'valt.' . $this->getFieldByAlias('productId') . ' = ?',
					(int)$productId
				);

		$fieldTable = PTA_DB_Table::get('Catalog_Field');
		$select->join(
					array('fieldt' => $fieldTable->getTableName()),
					'valt.' . $this->getFieldByAlias('fieldId') . ' = fieldt.' . $fieldTable->getPrimary()
				);
		$select->setIntegrityCheck(false);
		return $this->fetchAll($select)->toArray();
	}
}
