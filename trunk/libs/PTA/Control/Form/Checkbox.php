<?php
class PTA_Control_Form_Checkbox extends PTA_Control_Form_Field 
{
    /**
     * 
     */
    
    public function init()
    {
        parent::init();

        $this->setFieldType(PTA_Control_Form_Field::TYPE_CHECKBOX);
    }
    
    public function setChecked($cheked)
    {
        $this->setValue($cheked);
    }
    
    public function getChecked()
    {
        return $this->getValue();
    }
    
}
