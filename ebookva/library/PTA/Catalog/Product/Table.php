<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Table.php 5 2008-12-27 18:39:21Z TPavuk $
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
    
}
