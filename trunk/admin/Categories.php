<?php

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
        $itemId = $this->getApp()->getItem();

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
            
            case 'Fields':
                    $this->fieldsAction();
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
        $this->setVar('tplMode', 'list');
        
        $view = new PTA_Control_View('categoriesView', $this->_category);
        $this->addActions($view);
        
        $res = $view->exec();
        
        $this->setVar('view', $res);
    }
    
    public function addActions(&$view)
    {
        $view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.gif');
        
        $view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/', 'edit.gif');
        $view->addCommonAction('Fields', $this->getModuleUrl() . 'Fields/', 'edit.gif');
        $view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/', 'erase.gif');
    }
    
    public function deleteAction()
    {
        $this->_category->remove();
        $this->redirect($this->getModuleUrl());
    }
    
    public function fieldsAction()
    {
        $this->setVar('tplMode', 'categoryFields');
        
        $editForm = new Categories_editFieldsForm('editFieldsForm', $this->_category);
        $this->addVisual($editForm);
        
        
    }
        
}
