<?php
class Manufacturers_editForm extends PTA_Control_Form 
{
    /**
     * manufacturer
     *
     * @var PTA_Catalog_Manufacturer
     */
    private $_manufacturer;
    
    /**
     * @param string $prefix
     * @param PTA_Catalog_Manufacturer $manufacturer
     */
    public function __construct($prefix, $manufacturer)
    {
        $this->_manufacturer = $manufacturer;
        
        parent::__construct($prefix);
        
        $this->setTitle('Manufacturers Edit Form');
    }
    
    public function initForm()
    {
        $title = new PTA_Control_Form_Text('title', 'Manufacturer Title', true, '');
        $title->setSortOrder(100);
        $this->addVisual($title);
        
        $url = new PTA_Control_Form_Text('url', 'Manufacturer URL', false, '');
        $url->setSortOrder(200);
        $this->addVisual($url);
        
        $submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
        $submit->setSortOrder(300);
        $this->addVisual($submit);
    }
    
    public function onLoad()
    {
        $data = new stdClass();
/*        
        $data->title = $this->_manufacturer->getTitle();
        $data->url = $this->_manufacturer->getUrl();
*/
        $this->_manufacturer->loadTo($data);
        
        $data->submit = 'Save';
        
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
        
        $this->_manufacturer->loadFrom($data);
//var_dump($data);        

        if ($this->_manufacturer->save()) {
            $this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
        }

    }
    
    
}
