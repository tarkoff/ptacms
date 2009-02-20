<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Header.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Header extends PTA_WebModule
{
    /**
     * 
     */
    function __construct ($prefix)
    {
        parent::__construct($prefix, 'Header.tpl');
        
        $this->getApp()->insertModule('MainMenu', 'MainMenu');
    }
    
    public function init()
    {
        
    }
    
}
