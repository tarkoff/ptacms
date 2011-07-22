<?php
/**
 *  PTA App Guest
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Guest.php 153 2009-09-11 12:57:59Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_User_Guest extends PTA_User
{
	public function __construct($prefix)
	{
		parent::__construct($prefix);
		//$this->loadById($this->_table->getGuestId(true));
		$this->loadFrom(current($this->getTable()->getGuest()));
	}
}
