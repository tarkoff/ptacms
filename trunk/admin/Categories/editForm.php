<?php
class Categories_editForm extends PTA_Control_Form 
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
        $title = new PTA_Control_Form_Text('title', 'Category Title', true, '');
        
        $title->setSortOrder(100);
        $this->addVisual($title);
        $title->setCssClass('textField');

        $CategoriesArray = $this->_category->getAll();
        $values = array(array(0 , 'Empty'));
        
        if (!empty($CategoriesArray)) {
            foreach ($CategoriesArray as $category) {
                if ($category->getId() == $this->_category->getId()) {
                    continue;
                }
                $values[] = array($category->getId(), $category->getTitle());
            }
        }

        $categorys = new PTA_Control_Form_Select('parentId', 'Parent Category', false, $values);
        $categorys->setSortOrder(200);
        $categorys->setSelected(2);
        $categorys->setCssClass('textField');
        $this->addVisual($categorys);

        $submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
        $submit->setSortOrder(300);
        $this->addVisual($submit);
    }
    
    public function onLoad()
    {
        $data = new stdClass();
        
        $this->_category->loadTo($data);
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
        
        $this->_category->loadFrom($data);        
        
        if ($this->_category->save()) {
            $this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
        }
    }
    
    
}
