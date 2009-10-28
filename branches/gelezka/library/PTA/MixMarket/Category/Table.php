<?php
/**
 * MixMarket Category Table
 *
 * @package PTA_MixMarket
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 120 2009-07-27 17:34:05Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Category_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'MIXMARKET_CATEGORIES';
	protected $_primary = 'CATEGORIES_ID';

}
