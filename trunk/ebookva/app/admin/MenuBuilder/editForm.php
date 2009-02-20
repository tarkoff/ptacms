<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: editForm.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

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