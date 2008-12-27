<?php
class PTA_Control_Form_Text extends PTA_Control_Form_Field 
{
    /**
     * 
     */
    
    public function init()
    {
        parent::init();

        $this->setFieldType(PTA_Control_Form_Field::TYPE_TEXT);
    }
    
}
