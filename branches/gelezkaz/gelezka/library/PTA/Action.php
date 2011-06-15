<?php
/**
 * App Action
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Action extends PTA_Object
{
	protected $_actions = array();

	
	public function getActions()
	{
		return $this->_actions;
	}

	public function addAction($action)
	{
		$this->_actions[] = $action;
	}

}
