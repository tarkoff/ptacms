<?php

class Fields extends PTA_WebModule
{
    private $_field;
    
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Fields.tpl');
        
        $this->_field = new PTA_Catalog_Field('Field');
        
        $this->setModuleUrl(ADMINURL . '/Fields/');
        
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
            $this->_field->loadById($itemId);
        }
        
        $editForm = new Fields_editForm('editForm', $this->_field, $copy);
        $this->addVisual($editForm);
    }
    
    public function listAction()
    {
        $this->setVar('tplMode', 'list');
        $fieldTable = $this->_field->getTable();
                
        $fields = $fieldTable->getFields();
        unset($fields['CATEGORYID']);
        
        $view = new PTA_Control_View('fieldsView', $this->_field, array_values($fields));

        
        $category = new PTA_Catalog_Category('Category');
        $categoryTable = $category->getTable();
        
        $view->join(
                $categoryTable->getTableName(), 
        		($fieldTable->getFullFieldName('CATEGORYID') . ' = ' . $categoryTable->getFullPrimary()), 
                array($fieldTable->getTableName() . '_CATEGORY' => $categoryTable->getFieldByAlias('TITLE'))
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
            $this->_field->loadById($itemId);
        }
        
        $this->_field->remove();

        $this->redirect($this->getModuleUrl());
    }
    
}
