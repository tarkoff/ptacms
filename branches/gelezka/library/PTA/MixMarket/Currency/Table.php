<?php
/**
 * MixMarket Currency Table
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 129 2009-07-29 18:59:13Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Currency_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'MIXMARKET_CURRENCIES';
	protected $_primary = 'CURRENCIES_ID';

}
