<?php
/**
 * Menu
 *  
 * @author taras
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class UDL_Menu extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'MENUS';
    protected $_primary = 'MENUID';
    protected $_sequence = true;    

    public function getLeftMenu()
    {
        return $this->fetchAll()->toArray();
    }
    
    public function getMenuItemById($id)
    {
        $select = $this->select()->where('MENUID = ?', (int)$id);
                                
        $row = $this->fetchRow($select);
        
        return $row;
    }
}
