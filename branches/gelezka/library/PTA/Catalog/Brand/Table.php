<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 43 2009-04-23 21:29:55Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Brand_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'BRANDS';
	protected $_primary = 'BRANDS_ID';
	protected $_sequence = true;
	
	
}
