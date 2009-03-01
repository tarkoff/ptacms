<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Categories extends PTA_WebModule
{
    private $_category;
    
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Categories.tpl');
        
        $this->setModuleUrl(ADMINURL . '/Categories/');
        $this->_category = new PTA_Catalog_Category('Category');        
    }
    
    public function init()
    {
        parent::init();
        
        $action = $this->getApp()->getAction();
        $itemId = $this->getApp()->getHttpVar('Category');
        $this->setVar('tplMode', 'list');
        
        if (!empty($itemId)) {
            $this->_category->loadById($itemId);
        }

        switch (ucfirst($action)) {
            case 'Add': 
                    $this->editAction();
            break;
            
            case 'List':
                    $this->listAction();
            break;
            
            case 'Edit':
                    $this->editAction();
            break;
            
            case 'AddProduct':
                    $this->addProductAction();
            break;
            
            case 'AddFields':
                    $this->addFieldsAction();
            break;
            
            case 'DelFields':
                    $this->delFieldsAction();
            break;
            
            case 'Delete':
                $this->deleteAction();
            break;
            
            default:
                $this->listAction();
        }
    }
    
    public function editAction()
    {
        $this->setVar('tplMode', 'edit');
        
        $editForm = new Categories_editForm('editForm', $this->_category);
        $this->addVisual($editForm);
    }
    
    public function listAction()
    {        
        $view = new PTA_Control_View('categoriesView', $this->_category);
        $this->addActions($view);
        
        $res = $view->exec();
        
        $this->setVar('view', $res);
    }
    
    public function addActions(&$view)
    {
        $view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');
        
        $view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Category', 'edit.png');
        $view->addCommonAction('Add Fields', $this->getModuleUrl() . 'addFields/Category', 'fields.png');
        $view->addCommonAction('Remove Fields', $this->getModuleUrl() . 'delFields/Category', 'fields.png');
        $view->addCommonAction('Add Product', $this->getModuleUrl() . 'addProduct/Category', 'addItem.png');
        $view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Category', 'remove.png');
    }
    
    public function deleteAction()
    {
        $this->_category->remove();
        $this->redirect($this->getModuleUrl());
    }
    
    public function addFieldsAction()
    {
        $this->setVar('tplMode', 'addFields');
        
        $editForm = new Categories_addFieldsForm('addFieldsForm', $this->_category);
        $this->addVisual($editForm);
    }
        
    public function delFieldsAction()
    {
        $this->setVar('tplMode', 'delFields');
        
        $editForm = new Categories_delFieldsForm('delFieldsForm', $this->_category);
        $this->addVisual($editForm);
    }
    
    public function addProductAction()
    {
        $this->setVar('tplMode', 'addProduct');
        
        $product = new PTA_Catalog_Product('product');
        $product->setCategoryId($this->_category->getId());
        $editForm = new Categories_addProductForm('addProductForm', $product);
        $this->addVisual($editForm);
    }
}
