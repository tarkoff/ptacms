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

class PTA_Catalog_Product_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'PRODUCTS';
	protected $_primary = 'PRODUCTS_ID';
	protected $_sequence = true;
	protected $_product;
	
	public function getFields()
	{
		$staticFields = parent::getFields();
		$dynamicFields = $this->_product->getCustomFields();

		return array_merge($staticFields, $dynamicFields);
	}
	
	public function setProduct($product)
	{
		$this->_product = $product;
	}
}
