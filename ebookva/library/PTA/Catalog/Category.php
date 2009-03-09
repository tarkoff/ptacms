<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category extends PTA_DB_Object 
{

    private $_parentId;
    private $_title;
    private $_isDefault;
    
    /**
     * 
     */
    
    public function getParentId()
    {
        return $this->_parentId;
    }
    
    public function setParentId($value)
    {
        $this->_parentId = (int)$value;
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
    }
    
    public function getIsDefault()
    {
        return $this->_isDefault;
    }
    
    public function setIsDefault($isDefault)
    {
        $this->_isDefault = (int)$isDefault;
    }
}