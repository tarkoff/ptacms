<?php
class Catalog_editForm extends PTA_Control_Form 
{
    private $_product;
    private $_copy;
    /**
     * 
     */
    public function __construct($prefix, $product, $copy = false)
    {
        $this->_product = $product;
        $this->_copy = $copy;
        
        parent::__construct($prefix);
        
        $this->setTitle('Products Edit Form');
    }
    
    public function initForm()
    {
        $fieldsTable = new PTA_Catalog_Field_Table();
var_dump($this->_product->getCategoryId());        
        $productfields = $fieldsTable->getFieldsByCategory($this->_product->getCategoryId());
var_dump($productfields);
/*
        $title = new PTA_Control_Form_Text('title', 'Field Title', true, '');
        $title->setSortOrder(100);
        $title->setCssClass('textField');
        $this->addVisual($title);

        $alias = new PTA_Control_Form_Text('alias', 'Field Alias', true, '');
        $alias->setSortOrder(200);
        $alias->setCssClass('textField');
        $this->addVisual($alias);
        
        $category = new PTA_Category('Category');
        $CategoriesArray = $category->getAll();

        if (!empty($CategoriesArray)) {
            foreach ($CategoriesArray as $catalog) {
                $values[] = array($catalog->getId(), $field->getTitle());
            }
        }

        $categories = new PTA_Control_Form_Select('categoryId', 'Parent Category', false, $values);
        $categories->setSortOrder(300);
        $categories->setSelected(2);
        $categories->setCssClass('textField');
        $this->addVisual($categories);

        $submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
        $submit->setSortOrder(400);
        $this->addVisual($submit);
*/
    }
    
    public function onLoad()
    {
        $data = new stdClass();
        
//        $this->_catalog->loadTo($data);
        $data->submit = 'save';
        
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
        
        $this->_field->loadFrom($data);
        
        if ($this->_copy) {
            $this->_field->setId(null);
        }
                
        if ($this->_field->save() || $this->_copy) {
            $this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
        }
        
        return true;
    }
    
    
}
