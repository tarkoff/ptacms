<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: MenuBuilder.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class MenuBuilder extends PTA_WebModule
{
    private $_menu;
    
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'menuBuilder.tpl');
        
        $this->_menu = new UDL_Menu();
    }
    
    public function init()
    {
        parent::init();
        
        $action = $this->getApp()->getAction();
        $model = $this->getApp()->getModel();
        
        switch ($action) {
            case 'Add': 
                    $this->addAction();
            break;
            
            case 'List':
                    $this->listAction();
            break;
            
            case 'Edit':
                    $this->editAction($model);
            break;
        }
    }
    
    public function editAction($id=null)
    {
        $this->setVar('tplMode', 'edit');
        
    }
    
    public function listAction()
    {
        $this->setVar('tplMode', 'list');
    }
        
}
