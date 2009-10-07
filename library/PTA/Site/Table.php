<?php
/**
 * Site Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 111 2009-07-20 13:27:18Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Site_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'SITES';
	protected $_primary = 'SITE_ID';

}
