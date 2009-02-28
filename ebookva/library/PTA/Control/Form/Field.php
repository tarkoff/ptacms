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
    const TYPE_TEXT = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_RADIOGROUP = 3;
    const TYPE_SUBMIT = 4;
    const TYPE_IMAGE = 5;
    const TYPE_SELECT = 6;
    const TYPE_CHECKBOX = 7;
    const TYPE_FIELDSGROUP = 8;
    
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
    
    public static function getPossibleFields()
    {
    	return array(
    				array(self::TYPE_TEXT, 'Text'),
    				array(self::TYPE_TEXTAREA, 'Text Area'),
    				array(self::TYPE_RADIOGROUP, 'Radio Group'),
    				array(self::TYPE_SUBMIT, 'Submit'),
    				array(self::TYPE_IMAGE, 'Image'),
    				array(self::TYPE_SELECT, 'Select'),
    				array(self::TYPE_CHECKBOX, 'Checkbox'),
    				array(self::TYPE_FIELDSGROUP, 'Fields Group')
    			);
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
        
    public function setIndex($value)
    {
        $this->setVar('index', $value);
    }
    
    public function getIndex()
    {
        return $this->getVar('index');
    }
}
