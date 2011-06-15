<?php
/**
 * User Site Header Controller
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Header.php 106 2009-07-17 17:48:15Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Contacts extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Contacts.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Products/View/Product');
	}

	public function init()
	{
		if ($this->isActive()) {
			$this->viewAction();
		}
	}

	public function viewAction()
	{
		
	}

}
