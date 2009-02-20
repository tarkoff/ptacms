<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Checkbox.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

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
        $this->setVar('checked', (int)$cheked);
    }
    
    public function getChecked()
    {
        return $this->getVar('checked');
    }
    
}
