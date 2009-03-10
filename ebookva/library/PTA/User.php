<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2009 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: TemplateEngine.php 13 2009-02-28 14:47:29Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_User extends PTA_DB_Object 
{
	protected $_login;
	protected $_passwd;
	protected $_sessionHash;
	
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
		return $this->_passwd;
	}
	
	public function setPassword($passwd)
	{
		$this->_passwd = $passwd;
	}
	
	public function getSessionHash()
	{
		if (empty($this->_sessionHash)) {
			$this->_sessionHash = md5("{$this->_login}_" . date());
		}
		
		return $this->_sessionHash;
	}
	
	public function setSessionHash($hash)
	{
		$this->_sessionHash = $hash;
	}
	
	public static function getPasswdHash($passwd)
	{
		return sha1(md5($passwd));
	}
}
