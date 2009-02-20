<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Catalog.php 4 2008-12-27 16:43:31Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog extends PTA_WebModule
{
    private $_catalog;
    
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Catalog.tpl');
        
        $this->_catalog = new PTA_Catalog_Product('Catalog');
        
        $this->setModuleUrl(ADMINURL . '/Catalog/');
        
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
            
            case 'Copy':
                $this->editAction($item, true);
            break;
            
            default:
                $this->listAction();
        }
    }
    
    public function editAction($itemId = null, $copy = false)
    {
        $this->setVar('tplMode', 'edit');
        
        if (!empty($itemId)) {
            $this->_catalog->loadById($itemId);
        }
        
        $editForm = new Catalog_editForm('editForm', $this->_catalog, $copy);
        $this->addVisual($editForm);
    }
    
    public function listAction()
    {
        $this->setVar('tplMode', 'list');
        $catalogTable = $this->_catalog->getTable();
                
        $catalog = $catalogTable->getFields();
        unset($catalog['CATEGORYID']);
        
        $view = new PTA_Control_View('catalogView', $this->_catalog, array_values($catalog));

        
        $category = new PTA_Catalog_Category('Category');
        $categoryTable = $category->getTable();
        
        $view->join(
                $categoryTable->getTableName(), 
        		($catalogTable->getFullFieldName('CATEGORYID') . ' = ' . $categoryTable->getFullPrimary()), 
                array($catalogTable->getTableName() . '_CATEGORY' => $categoryTable->getFieldByAlias('TITLE'))
               );

        $this->addActions($view);
        $res = $view->exec();
        
        $this->setVar('view', $res);
    }
    
    public function addActions(&$view)
    {
        $view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.gif');
        
        $view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/', 'edit.gif');
        $view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/', 'edit.gif');
        $view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/', 'erase.gif');
    }
    
    public function deleteAction($itemId)
    {
        if (!empty($itemId)) {
            $this->_catalog->loadById($itemId);
        }
        
        $this->_catalog->remove();

        $this->redirect($this->getModuleUrl());
    }
    
}
