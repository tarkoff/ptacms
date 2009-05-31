<?php
/**
 * Catalog Product Table
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PRODUCTS';
	protected $_primary = 'PRODUCTS_ID';
	protected $_sequence = true;
	protected $_product;
	
	/**
	 * Get category fields with custom fields
	 *
	 * @return unknown
	 */
	public function getFields()
	{
		$fields = parent::getFields();

		if (!empty($this->_product)) {
			$fields = array_merge($fields, $this->_product->getCustomFields());
		}

		return $fields;
	}
	
	public function setProduct($product)
	{
		$this->_product = $product;
	}
}
