<?php
/**
 * Products Prices Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 129 2009-07-29 18:59:13Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Price_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PRICES';
	protected $_primary = 'PRICES_ID';

	public function getPrices($productId)
	{
		if (empty($productId)) {
			return array();
		}

		return $this->fetchAll(
			$this->select()->where($this->getFieldByAlias('productId') . ' = ' . (int)$productId)
		)->toArray();
	}
	
	public function getSecondHandPrices($productId = null)
	{
		$guestId = PTA_DB_Object::get('User_Guest')->getId();
		$currencyTable = self::get('Catalog_Currency');
		
		$select = $this->select()->from(
			array('prices' => $this->getTableName())
		);

		$select->join(
			array('currencies' => $currencyTable->getTableName()),
			'prices.' . $this->getFieldByAlias('currency') 
			. ' = currencies.' . $currencyTable->getPrimary(),
			array('CURRENCY' => $currencyTable->getFieldByAlias('reduction'))
		);

		$select->setIntegrityCheck(false);

		if (!empty($productId)) {
			$select->where('prices.' . $this->getFieldByAlias('productId') . ' = ' . intval($productId));
		}
		
		$select->order($this->getPrimary() . ' DESC');

		return $this->fetchAll($select)->toArray();
	}
}
