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
class Categories_addProductForm extends PTA_Control_Form 
{
    private $_category;
    /**
     * 
     */
    public function __construct($prefix, $category)
    {
        $this->_category = $category;
        
        parent::__construct($prefix);
        
        $this->setTitle('Remove Fields From "' . $category->getTitle() . '" Category');
    }
    
    public function initForm()
    {
        $categoryFieldTable = new PTA_Catalog_CategoryField_Table();
        $fieldsTable = new PTA_Catalog_Field_Table();
        
        $categoryFields = (array)$categoryFieldTable->getFieldsByCategory($this->_category->getId(), true, true);
var_dump($categoryFields);
		$name = $fieldsTable->getFieldByAlias('alias');
		$title = $fieldsTable->getFieldByAlias('title');
		$sortOrder = $categoryFieldTable->getFieldByAlias('sortOrder');
		$fieldId = $categoryFieldTable->getFieldByAlias('fieldId');
		$fieldType = $fieldsTable->getFieldByAlias('fieldType');

		foreach ($categoryFields as $fieldArray) {
			$options = array(
						'name' => $fieldArray[$name],
						'label' => $fieldArray[$title],
						'sortOrder' => $fieldArray[$sortOrder]
			);
			
			$field = PTA_Control_Form_Field::getFieldByType(
													$fieldArray[$fieldType], 
													"{$fieldArray[$name]}_{$fieldArray[$fieldId]}",
													$options
											);
			if (!empty($field)) {
				$this->addVisual($field);
			}
		}
        $submit = new PTA_Control_Form_Submit('submit', 'Remove Field', true, 'Save');
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
        $data->submit = 'Remove Field';

        return $data;
    }
    
    public function onSubmit(&$data)
    {
        $invalidFields = $this->validate($data);
        if (!empty($invalidFields)) {
            foreach ($invalidFields as $field) {
                echo 'Field "' . $field->getLabel() . '" is required!<br />';
            }
            
            return false;
        }


        if (!empty($data->categoryFieldId)) {
        	$categoryFieldId = (int)$data->categoryFieldId;
        	$categoryField = new PTA_Catalog_CategoryField('delField');
        	$categoryField = $categoryField->loadById($categoryFieldId);

        	if ($categoryField->remove()) {
            	$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
        	}
        }
        
    }
    
    
}
