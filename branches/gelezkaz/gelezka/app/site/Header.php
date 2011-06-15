<?php
/**
 * User Site Header Controller
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Header extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Header.tpl');
		//$this->getApp()->insertModule('TopMenu', 'TopMenu');
	}

	public function init()
	{

	}

}
