<?php
/**
 * Product Settings Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 65 2009-06-04 21:30:33Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Settings_Table extends PTA_DB_Table
{
	/**
	 * The default table name
	 */
	protected $_name = 'CATALOG_PRODUCTSETTINGS';
	protected $_primary = 'PRODUCTSETTINGS_PRODUCTID';

}
