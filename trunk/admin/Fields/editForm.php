<?php
class Fields_editForm extends PTA_Control_Form 
{
    private $_field;
    private $_copy;
    /**
     * 
     */
    public function __construct($prefix, $field, $copy = false)
    {
        $this->_field = $field;
        $this->_copy = $copy;
        
        parent::__construct($prefix);
        
        $this->setTitle('Field Edit Form');
    }
    
    public function initForm()
    {
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
            foreach ($CategoriesArray as $field) {
                $values[] = array($field->getId(), $field->getTitle());
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
    }
    
    public function onLoad()
    {
        $data = new stdClass();
        
        $this->_field->loadTo($data);
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
