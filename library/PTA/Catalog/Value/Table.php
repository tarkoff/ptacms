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

	public function getValuesByProductId($productId)
	{
		if (empty($productId)) {
			return array();
		}

		$categoryFieldTable = PTA_DB_Table::get('Catalog_Category_Field');
		$fieldTable = PTA_DB_Table::get('Catalog_Field');
		$fieldValueTable = PTA_DB_Table::get('Catalog_Field_Value');

/*
SELECT pv.PRODUCTSVALUES_FIELDID, pv.PRODUCTSVALUES_VALUEID, pf.PRODUCTSFIELDS_TITLE, pfv.PRODUCTSFIELDSVALUES_VALUE
FROM CATALOG_PRODUCTSVALUES AS pv
INNER JOIN CATALOG_PRODUCTSFIELDSVALUES AS pfv ON pv.PRODUCTSVALUES_VALUEID = pfv.PRODUCTSFIELDSVALUES_ID
INNER JOIN CATALOG_CATEGORIESFIELDS AS cf ON pv.PRODUCTSVALUES_FIELDID = cf.CATEGORIESFIELDS_ID
INNER JOIN CATALOG_PRODUCTSFIELDS AS pf ON pfv.PRODUCTSFIELDSVALUES_FIELDID = pf.PRODUCTSFIELDS_ID
WHERE pv.PRODUCTSVALUES_PRODUCTID =3
ORDER BY cf.CATEGORIESFIELDS_SORTORDER
 */

		$select = $this->select()->from(
			array('pv' => $this->getTableName()),
			array(
				$this->getFieldByAlias('fieldId'),
				$this->getFieldByAlias('valueId')
			)
		);

		$select->join(
			array('pfv' => $fieldValueTable->getTableName()),
			'pv.' . $this->getFieldByAlias('valueId')
			. ' = pfv.' . $fieldValueTable->getPrimary(),
			array($fieldValueTable->getFieldByAlias('value'))
		);

		$select->join(
			array('cf' => $categoryFieldTable->getTableName()),
			'pv.' . $this->getFieldByAlias('fieldId') 
			. ' = cf.' . $categoryFieldTable->getPrimary(),
			array()
		);

		$select->join(
			array('pf' => $fieldTable->getTableName()),
			'pfv.' . $fieldValueTable->getFieldByAlias('fieldId') . ' = pf.' . $fieldTable->getPrimary(),
			array($fieldTable->getFieldByAlias('title'))
		);

		$select->where(
			'pv.' . $this->getFieldByAlias('productId') . ' = ' . (int)$productId
		);

		$select->order('cf.' . $categoryFieldTable->getFieldByAlias('sortOrder'));
		$select->setIntegrityCheck(false);

		return $this->fetchAll($select)->toArray();
	}
}
