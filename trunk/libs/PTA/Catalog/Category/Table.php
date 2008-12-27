<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category_Table extends PTA_DB_Table 
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
    
    public function getDefaultCategory()
    {
        $select = $this->select()->where($this->getFieldByAlias('isdefault') . ' = ?');
        
        return $this->fetchRow($select)->toArray();
    }
    
}
