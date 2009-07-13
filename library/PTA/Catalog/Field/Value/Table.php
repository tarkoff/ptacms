<?php
/**
 * Product Field Value Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Value_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PRODUCTSFIELDSVALUES';
	protected $_primary = 'PRODUCTSFIELDSVALUES_ID';
	
	public function getFieldValues($fieldIds)
	{
		$fieldIds = (array)$fieldIds;
		return $this->findByFields(
			array('fieldId'),
			array($fieldIds)
		);
	}
	
	public function saveFieldValues($values)
	{
		$values = (array)$values;
		if (empty($values)) {
			return false;
		}
		
		$primaryField = $this->getPrimary();
		$valueField = $this->getFieldByAlias('value');
//		$fieldIdField = $this->getFieldByAlias('fieldId');
		
		$adapter = $this->getAdapter();
		$adapter->beginTransaction();
		foreach ($values as $valueId => $value) {
			$this->update(
				array($valueField => $value),
				$adapter->quoteInto($primaryField . ' = ?', intval($valueId))
			);
		}
		return $adapter->commit();
	}
	
}
