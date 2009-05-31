<?php
/**
 * Catalog Field Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_PRODUCTSFIELDS';
	protected $_primary = 'PRODUCTSFIELDS_ID';
	protected $_sequence = true;
	
}
