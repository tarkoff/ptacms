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
class Categories_editFieldsForm extends PTA_Control_Form 
{
    private $_category;
    /**
     * 
     */
    public function __construct($prefix, $category)
    {
        $this->_category = $category;
        
        parent::__construct($prefix);
        
        $this->setTitle('Categorys Edit Form');
    }
    
    public function initForm()
    {
        $categoryFieldTable = new PTA_Catalog_CategoryField_Table();
        
        $categoryFields = (array)$categoryFieldTable->getFieldsByCategory($this->_category->getId());
        
        $selectedFieldsIds = array();
        foreach ($categoryFields as $catField) {
            if (!empty($catField[$categoryFieldTable->getFieldByAlias('categoryId')])) {
                $selectedFieldsIds[] = $catField[$categoryFieldTable->getFieldByAlias('categoryId')];
            }
        }

        $fieldsTable = new PTA_Catalog_Field_Table();
        $allFileds = (array)$fieldsTable->getAll();
        
        $this->setVar('selectedFields', $selectedFieldsIds);        
        
        foreach ($allFileds as $field) {
            $fieldId = $field[$fieldsTable->getPrimary()];
           
            $sortOrder = new PTA_Control_Form_Text("sortOrder[{$fieldId}]", '', false, '0');
            $sortOrder->setLabel($field[$fieldsTable->getFieldByAlias('title')]);
            $sortOrder->setProperty('fieldId', $fieldId);
            $this->addVisual($sortOrder);
            
        }
        
        $submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
        $submit->setSortOrder(300);
        $this->addVisual($submit);
    }
    
    public function onLoad()
    {
        $data = new stdClass();
        
        //$this->_category->loadTo($data);
        $data->submit = 'save';

//var_dump($data);        
        return $data;
    }
    
    public function onSubmit(&$data)
    {
        $invalidFields = $this->validate($data);
        if (!empty($invalidFields)) {
            foreach ($invalidFields as $field) {
                echo 'Filed ' . $field->getLabel() . ' is required!<br />';
            }
            
            return false;
        }
        $fields = array_keys($this->getHttpVar($this->getPrefix() . '_fields'));
        $sortOrders = (array)@$data->sortOrder;
//$data->sortOrder = $this->getHttpVar($this->getPrefix() . '_sortOrder');        
        $categoryFieldTable = new PTA_Catalog_CategoryField_Table();
        $categoryFieldTable->clearbyCategoryId($this->_category->getId());
//var_dump($data);        
        foreach ($fields as $fieldId) {
            $sortOrder = (!empty($sortOrders[$fieldId]) ? (int)$sortOrders[$fieldId] : 0);
            $field = new PTA_Catalog_CategoryField('field_' . $fieldId);
            $field->setCategoryid($this->_category->getId());
            $field->setFieldId($fieldId);
            $field->setSortOrder((int)$sortOrders[$fieldId]);
            $field->save();
        }
        
        if ($this->_category->save()) {
            $this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
        }
    }
    
    
}
