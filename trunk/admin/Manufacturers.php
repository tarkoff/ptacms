<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Manufacturers extends PTA_WebModule
{
    private $_manufacturer;
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Manufacturers.tpl');
        
        $this->setModuleUrl(ADMINURL . '/Manufacturers/');
        
        $this->_manufacturer = new PTA_Catalog_Manufacturer('Manufacturer');        
    }
    
    public function init()
    {
        parent::init();

        $action = $this->getApp()->getAction();
        $item = $this->getApp()->getItem();

        switch (ucfirst($action)) {
            case 'Add': 
                    $this->editAction();
            break;
            
            case 'List':
                    $this->listAction();
            break;
            
            case 'Edit':
                    $this->editAction($item);
            break;
            
            case 'Delete':
                $this->deleteAction($item);
            break;
            
            default:
                $this->listAction();
        }
    }
    
    public function editAction($itemId = null)
    {
        $this->setVar('tplMode', 'edit');
        
        if (!empty($itemId)) {
            $this->_manufacturer->loadById($itemId);
        }

        $editForm = new Manufacturers_editForm('editForm', $this->_manufacturer);
        $this->addVisual($editForm);
    }
    
    public function listAction()
    {
        $this->setVar('tplMode', 'list');
        
        $view = new PTA_Control_View('view', $this->_manufacturer);
        $view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.gif');
        
        $view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/', 'edit.gif');
        $view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/', 'erase.gif');
        
        $res = $view->exec();
        $this->setVar('view', $res);
    }
 
    public function deleteAction($itemId)
    {
        if (!empty($itemId)) {
            $this->_manufacturer->loadById($itemId);
        }
        
        $this->_manufacturer->remove();

        $this->redirect($this->getModuleUrl());
    }
        
}
