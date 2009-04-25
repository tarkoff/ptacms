<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Header.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Header extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Header.tpl');
		$this->getApp()->insertModule('TopMenu', 'TopMenu');
	}

	public function init()
	{
		
	}

}
