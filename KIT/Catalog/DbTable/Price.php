<?php
/**
 * Catalog Price Database Table
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
 * @version    $Id: Field.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_DbTable_PRice extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_PRICES';
	protected $_primary = 'PRICE_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$productsTable   = self::get('KIT_Catalog_DbTable_Product');
		$brandsTable     = self::get('KIT_Catalog_DbTable_Brand');
		$currenciesTable = self::get('KIT_Catalog_DbTable_Currency');

		$select = $this->getAdapter()->select();

		$select->from(
			array('prices' => $this->_name),
			array(
				$this->getPrimary(),
				'PRICE_PRODUCTID' => "CONCAT_WS(' ', brands."
										  . $brandsTable->getFieldByAlias('title') . ', '
										  . $productsTable->getFieldByAlias('title') . ')',
				$this->getFieldByAlias('cost'),
				$this->getFieldByAlias('currencyId') => 'currency.' . $currenciesTable->getFieldByAlias('reduction'),
				$this->getFieldByAlias('createDate'),
				$this->getFieldByAlias('actualTo'),
				$this->getFieldByAlias('author')
			)
		);

		$select->join(
			array('prods' => $productsTable->getTableName()),
			'prods.PRODUCTS_ID = prices.' . $this->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('brands' => $brandsTable->getTableName()),
			'prods.PRODUCTS_BRANDID = brands.' . $brandsTable->getPrimary(),
			array()
		);

		$select->join(
			array('currency' => $currenciesTable->getTableName()),
			'currency.' . $currenciesTable->getPrimary()
			. ' = prices.' . $this->getFieldByAlias('currencyId'),
			array()
		);

		$this->setViewSelect($select);
	}

	/**
	 * Get product prices
	 *
	 * @param int $productId
	 * @param boolean $orderAsc
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getPrices($productId, $orderAsc = true)
	{
		$productId = (int)$productId;
		if (empty($productId)) {
			return false;
		}

		return $this->fetchAll(
					$this->getFieldByAlias('productId') . ' = ' . $productId,
					array($this->getFieldByAlias('createDate') . ($orderAsc ? ' ASC' : ' DESC'))
			   );
	}
}
