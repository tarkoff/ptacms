<?php
/**
 * Catrgorys
 *  
 * @author taras
 * @version 
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
