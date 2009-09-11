<?php
/**
 *  PTA App User
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_User extends PTA_DB_Object 
{
	protected $_login;
	protected $_password;
	protected $_groupId;
	protected $_sessionHash;
	protected $_registerDate;
	protected $_accessRights = array();

	public function getLogin()
	{
		return $this->_login;
	}

	public function setLogin($login)
	{
		$this->_login = $login;
	}

	public function getPassword()
	{
		return $this->_password;
	}

	public function getGroupId()
	{
		return $this->_groupId;
	}

	public function setGroupId($groupId)
	{
		$this->_groupId = (int)$groupId;
	}

	public function setPassword($passwd)
	{
		$this->_password = $passwd;
	}

	public function getRegisterDate()
	{
		return $this->_registerDate;
	}

	public function setRegisterDate($date)
	{
		$this->_registerDate = $date;
	}

	public function getSessionHash()
	{
		if (empty($this->_sessionHash)) {
			$this->_sessionHash = md5("{$this->_login}_" . date("Ymd"));
		}

		return $this->_sessionHash;
	}

	public function setSessionHash($hash)
	{
		$this->_sessionHash = $hash;
	}

	public static function getPasswordHash($passwd)
	{
		return md5(sha1($passwd));
	}

	public function saveUserSession()
	{
		PTA_DB_Table::get('User_Stat')->saveUserSession($this);
	}
	
	public function hasRight(PTA_Module $module)
	{
		if (empty($this->_accessRights)) {
			$this->_accessRights = PTA_DB_Table::get('User_Rights')->getUserRights($this->getId());
		}

		if ($module->isPublic() || !empty($this->_accessRights[get_class($module)])) {
			return $this->_accessRights[get_class($module)];
		}

		return false;
	}
}
