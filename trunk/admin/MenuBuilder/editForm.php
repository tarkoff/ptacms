<?php
class MenuBuilder_editForm extends PTA_Control_Form 
{
    private $_menuItem;
    /**
     * 
     */
    public function __construct($prefix, $menuItem)
    {
        $this->_menuItem = $menuItem;
        
        parent::__construct($prefix);
    }
    
    public function intitForm()
    {
        
    }
    
    public function onLoad()
    {
        if (!empty($this->_menuItem)){
            $this->setVars($this->_menuItem);
        }
    }
    
    public function onSubmit()
    {
        
    }
}
?>