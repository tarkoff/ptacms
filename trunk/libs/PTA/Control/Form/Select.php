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

class PTA_Control_Form_Select extends PTA_Control_Form_Field 
{
    /**
     * 
     */
    function __construct ($prefix, $label = '', $mandatory = false, $options = array(), $value = null)
    {
            parent::__construct($prefix, $label, $mandatory, $value);
            $this->setOptions($options);
    }
    
    public function init()
    {
        parent::init();

        $this->setFieldType(PTA_Control_Form_Field::TYPE_SELECT);
    }
    
    public function setSelected($value)
    {
        $this->setValue($value);
    }
    
    public function getSelected()
    {
        return $this->getValue();
    }
    
    public function addOption($option)
    {
        $options = (array)$this->getOptions();
        
        $options[] = $option;
        $this->setOptions($options);
    }
    
    public function getOptions()
    {
        return $this->getVar('options');
    }
    
    public function setOptions($options)
    {
        $this->setVar('options', (array)$options);
    }
    
}
