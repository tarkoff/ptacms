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

class PTA_Category_Table extends PTA_DB_Table 
{
    /**
     * The default table name 
     */
    protected $_name = 'CATEGORIES';
    protected $_primary = 'CATEGORIES_ID';
    protected $_sequence = true;
    
    public function getCategoryById($categoryId)
    {
        return $this->find($categoryId)->toArray();
    }
    
}
