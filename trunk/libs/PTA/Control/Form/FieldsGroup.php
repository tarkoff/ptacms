<?php
class PTA_Control_Form_FieldsGroup extends PTA_Control_Form_Field 
{
    protected $_fields = array();

    /**
     * 
     */
    
    public function init()
    {
        parent::init();

        $this->setFieldType(PTA_Control_Form_Field::TYPE_FIELDSGROUP);
    }
    
    /**
     * add field to group
     *
     * @param PTA_Control_Form_Field $field
     */
    public function addField(PTA_Control_Form_Field $field)
    {
        $this->_fields[$field->getPrefix()] = $field;
    }
    
    /**
     * return field by prefix
     *
     * @param string $prefix
     */
    public function getField($prefix)
    {
        return (isset($this->_fields[$prefix]) ? $this->_fields[$prefix] : null);
    }
    
    /**
     * prepeare form for template
     *
     * @return stdClass
     */
    public function toString()
    {
        $object = parent::toString();
        

        $data = array();
        foreach ($this->_fields as $field) {
            $field->setName($this->getFormPrefix() . '_' . $field->getName());
            $data[$field->getPrefix()] = $field->toString();
        }
        
        usort($data, array('PTA_Control_Form', "sortData"));
        $object->data = $data;
         
        return $object;
    }
    
}
