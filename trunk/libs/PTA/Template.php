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

class PTA_Template extends PTA_Object {

    private $_file = null;
    private $_isIndex = false;
    
    public function __construct($prefix, $file)
    {
        $this->setPrefix($prefix);
        $this->_file = $file;
    }
    
    public function getFile()
    {
        return $this->_file;
    }
    
    public function isIndex()
    {
        return $this->_isIndex;
    }
    
    public function setIndex()
    {
        $this->_isIndex = true;
    }
    
    public function getTemplateFile()
    {
        return $this->_file;
    }
    
    public function getModule()
    {
        $this->getApp()->getModule($this->getPrefix());
    }
}
