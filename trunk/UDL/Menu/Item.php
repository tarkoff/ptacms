<?php
/**
 * Menu
 *  
 * @author taras
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class UDL_Menu_Item extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'MENUS';
    protected $_primary = 'MENUID';

    public function getLeftMenu()
    {
        return $this->fetchAll()->toArray();
    }
}
