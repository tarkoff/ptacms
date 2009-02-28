<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_Module extends PTA_Object 
{

	public function __construct($prefix)
	{
		$this->setPrefix($prefix);
	}

	public function init()
	{
	    parent::init();
	}
	
	public function run()
	{
	    parent::run();
	    
        if (!empty($this->_elements)) {
            foreach ($this->_elements as $element) {
                $element->run();
            }
        }
	}
	
	public function shutdown()
	{
	    parent::shutdown();
	}
	
	public function getModules()
	{
	    return $this->_subModules;
	}
	
	public function insertModule($prefix, $module)
	{
	    $this->getApp()->insertModule($prefix, $module);
	}
	
	public function getModule($prefix)
	{
	    return $this->getApp()->getModule($prefix);
	}
	
}
