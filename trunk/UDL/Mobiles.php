<?php
/**
 * Menu
 *  
 * @author taras
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class UDL_Mobiles extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'MOBILES';
    protected $_primary = 'MOBILEID';
    
    public function getPage($page, $rpp)
    {
        $select = $this->select()->limitPage($page, $rpp);
        
        return $this->fetchAll()->toArray();
    }

    
}
