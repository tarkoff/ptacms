<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: editFieldsForm.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Categories_delFieldsForm extends PTA_Control_Form 
{
    private $_category;
    /**
     * 
     */
    public function __construct($prefix, $category)
    {
        $this->_category = $category;
        
        parent::__construct($prefix);
        
        $this->setTitle('Category Fields Remove Form');
    }
    
    public function initForm()
    {
        $categoryFieldTable = new PTA_Catalog_CategoryField_Table();
        $fieldsTable = new PTA_Catalog_Field_Table();
        
        $categoryFields = (array)$categoryFieldTable->getFieldsByCategory($this->_category->getId());

        $select = new PTA_Control_Form_Select('fieldId', 'Fields For Adding', true);
        $select->setOptionsFromArray(
        			$categoryFields,
        			$categoryFieldTable->getPrimary(),
        			$fieldsTable->getFieldByAlias('title')
        		);
        $select->addOption(array(0, '- Empty -'));
        $select->setSortOrder(100);
        $this->addVisual($select);
        
        $submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
        $submit->setSortOrder(300);
        $this->addVisual($submit);
    }
    
    private function _filterFields($fields, $firstField, $secondField)
    {
    	$resData = array();
    	foreach ($fields as $field) {
    		$resData[] = array(@$field[$firstField], $field[$secondField]);
    	}
    	return $resData;
    }
    
    public function onLoad()
    {
        $data = new stdClass();
        
        //$this->_category->loadTo($data);
        $data->sortOrder = 10;
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


        if (!empty($data->fieldId)) {
        	$fieldId = (int)$data->fieldId;
        	$categoryField = new PTA_Catalog_CategoryField('field_' . $fieldId);
            $categoryField->setCategoryid($this->_category->getId());
            $categoryField->setFieldId($fieldId);
            if (empty($data->sortOrder)) {
            	$sortOrder = 0;
            } else {
            	$sortOrder = (int)$data->sortOrder;
            }
            $categoryField->setSortOrder($sortOrder);
        	if ($categoryField->save()) {
            	$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
        	}
        }
        
    }
    
    
}
