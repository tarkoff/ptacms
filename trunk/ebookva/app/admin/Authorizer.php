<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright	2009 PTA Studio
 * @license		http://framework.zend.com/license   BSD License
 * @version		$Id: Header.php 13 2009-02-28 14:47:29Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Authorizer extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Authorizer.tpl');
	}

	public function init()
	{
		parent::init();

		if ($this->loginByHash()) {
			return true;
		}
	}
	
	public function loginByHash()
	{
		$loginHash = $this->getApp()->getCookie('login');
		if (!empty($loginHash)) {
			//$userByHash = PTA_DB_Table::get('User')->
		}
	}

}
