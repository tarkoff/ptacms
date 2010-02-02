<?php
/**
 * Users Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Action.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

class Default_Model_User extends KIT_Model_Abstract
{
	protected $_login;
	protected $_password;
	protected $_firstName;
	protected $_lastName;
	protected $_groupId;
	protected $_registered;
	protected $_email;
	protected $_status;

	const STATUS_SUSPENDED = 0;
	const STATUS_ACTIVE = 1;

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

	public function setPassword($password)
	{
		$this->_password = $password;
	}

	public function getFirstName()
	{
		return $this->_firstName;
	}

	public function setFirstName($firstName)
	{
		$this->_firstName = $firstName;
	}

	public function getLastName()
	{
		return $this->_lastName;
	}

	public function setLastName($lastName)
	{
		$this->_lastName = $lastName;
	}

	public function getGroupId()
	{
		return $this->_groupId;
	}

	public function setGroupId($groupId)
	{
		$this->_groupId = (int)$groupId;
	}

	public function getRegistered()
	{
		return $this->_registered;
	}

	public function setRegistered($date)
	{
		$this->_registered = $date;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function setEmail($email)
	{
		$this->_email = $email;
	}

	public function getStatus()
	{
		return $this->_status;
	}

	public function setStatus($status)
	{
		$this->_status = $status;
	}
}