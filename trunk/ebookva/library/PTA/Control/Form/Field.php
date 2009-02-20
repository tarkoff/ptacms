<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Field.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_Control_Form_Field extends PTA_Object 
{
    const TYPE_TEXT = 'Text';
    const TYPE_TEXTAREA = 'TextArea';
    const TYPE_RADIOGROUP = 'RadioGroup';
    const TYPE_SUBMIT = 'Submit';
    const TYPE_IMAGE = 'Image';
    const TYPE_SELECT = 'Select';
    const TYPE_CHECKBOX = 'Checkbox';
    const TYPE_FIELDSGROUP = 'FieldsGroup';
    
    /**
     * 
     */
    function __construct ($prefix, $label = '', $mandatory = false, $value = null)
    {
        $this->setPrefix($prefix);
        $this->setLabel($label);
        $this->setMandatory($mandatory);
        $this->setValue($value);
    }
    
    public function setPrefix($value)
    {
        parent::setPrefix($value);
        $this->setName($value);
    }
    
    public function getValue()
    {
        return $this->getVar('value');
    }
    
    public function setValue($value)
    {
        $this->setVar('value', $value);
    }
    
    public function getLabel()
    {
        return $this->getVar('label');
    }
    
    public function setLabel($value)
    {
        $this->setVar('label', $value);
    }
    
    public function getName()
    {
        return $this->getVar('name');
    }
    
    public function setName($value)
    {
        $this->setVar('name', $value);
    }
    
    public function getCssClass()
    {
        return $this->getVar('cssClass');
    }
    
    public function setCssClass($value)
    {
        $this->setVar('cssClass', $value);
    }
    
    public function getFieldType()
    {
        return $this->getVar('type');
    }
    
    public function setFieldType($value)
    {
        $this->setVar('type', $value);
    }
    
    public function setSortOrder($value)
    {
        $this->setVar('sortOrder', (int)$value);
    }
    
    public function getSortOrder()
    {
        return $this->getVar('sortOrder');
    }
    
    public function setMandatory($value)
    {
        $this->setVar('mandatory', (int)$value);
    }
    
    public function isMamdatory()
    {
        return $this->getVar('mandatory');
    }
    
    public function setFormPrefix($value)
    {
        $this->setVar('formPrefix', (int)$value);
    }
    
    public function getFormPrefix()
    {
        return $this->getVar('formPrefix');
    }
    
    public function isDisabled()
    {
        return $this->getVar('disabled');
    }
    
    public function setDisabled($value = true)
    {
        $this->setVar('disabled', $value);
    }
    
    /**
     * add some var to template
     *
     * @param strimg $name
     * @param mixed $value
     */
    public function setProperty($name, $value)
    {
        if (!($properties = $this->getVar('properties'))) {
            $properties = new stdClass();
        }
        $properties->$name = $value;
        
        $this->setVar('properies', $properties);
    }
    
    public function getPtoperty($name)
    {
        if (($properties = $this->getVar('properties'))) {
            return (isset($properties->$name) ? $properties->$name : null);
        }
        
        return null;
    }
    
    /**
     * set mode for field like name="field[1]"...
     *
     * @param boolean $mode
     */
    public function setArrayMode($mode = false)
    {
        $this->setVar('arrayMode', (boolean)$mode);
    }
    
    /**
     * return current array mode
     *
     * @return boolean
     */
    public function getArrayMode()
    {
        return $this->getVar('arrayMode');
    }
    
    /**
     * set data for array mode
     *
     * @param array $data
     */
    public function setArrayData($data)
    {
        if (!empty($data)) {
            $this->setArrayMode(true);
            $this->setVar('arrayData', (array)$data);
        } else {
            $this->setArrayMode(false);
        }
    }
    
    public function setIndex($value)
    {
        $this->setVar('index', $value);
    }
    
    public function getIndex()
    {
        return $this->getVar('index');
    }
}
