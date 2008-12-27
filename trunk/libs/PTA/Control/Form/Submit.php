<?php
class PTA_Control_Form_Submit extends PTA_Control_Form_Field 
{
    /**
     * 
     */
    
    public function init()
    {
        parent::init();
        
        $this->setFieldType(PTA_Control_Form_Field::TYPE_SUBMIT);
        $this->setVar('isSubmit', '1');
    }
    
}
