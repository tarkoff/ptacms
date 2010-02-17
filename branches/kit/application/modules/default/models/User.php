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
 * @version    $Id$
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

	const ADMINISTRATOR_ID = 1;
	const GUeST_ID = 2;
	
	/**
	 * (non-PHPdoc)
	 * @see Model/KIT_Model_Abstract#save($data)
	 */
	public function save()
	{
		return parent::save(array('USERS_LOGIN' => $this->getLogin(),
							   'USERS_PASSWORD' => $this->getPassword(),
								'USERS_GROUPID' => $this->getGroupId(),
							  'USERS_FIRSTNAME' => $this->getFirstName(),
							   'USERS_LASTNAME' => $this->getLastName(),
								  'USERS_EMAIL' => $this->getEmail(),
								 'USERS_STATUS' => $this->getStatus(),
							 'USERS_REGISTERED' => $this->getRegistered()));
	}

	public static function getUserStatuses()
	{
		return array(
			self::STATUS_ACTIVE 	=> 'Active',
			self::STATUS_SUSPENDED 	=> 'Suspended'
		);
	}

	/**
	 * Get encoded password
	 *
	 * @param string $passwd
	 * @return string
	 */
	public static function getEncodedPassword($passwd)
	{
		return md5(sha1($passwd));
	}

	/**
	 * Get authorization adapter
	 *
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function getAuthAdapter()
	{
		$table = $this->getDbTable();
		$authAdapter = new Zend_Auth_Adapter_DbTable(
			$table->getAdapter(),
			$table->getTableName(),
			'USERS_LOGIN',
			'USERS_PASSWORD',
			'USERS_STATUS = ' . self::STATUS_ACTIVE
		);
		
		return $authAdapter;
	}
	
	public function authenticate()
	{
		$login = $this->getLogin();
		$password = $this->getPassword();

		if (empty($login)) {
			return false;
		}

		$authAdapter = $this->getAuthAdapter();
		$authAdapter->setIdentity($login)->setCredential($password);

		$auth = Zend_Auth::getInstance();
		$result  = $auth->authenticate($authAdapter);
		if (!$result->isValid()) {
			return false;
		}

		$auth->getStorage()->write($authAdapter->getResultRowObject(null, 'USERS_PASSWORD'));
		return true;
	}

	/**
	 * Check if user has rights for this resource
	 *
	 * @param int|Default_Model_Resource $resource
	 * @return boolean
	 */
	public function hasRight($resource)
	{
		if (!($resource instanceof Default_Model_Resource)) {
			$resourceId = intval($resource);
			$resource = new Default_Model_Resource();
			$resource->loadById($resourceId);
		}

		$resourceId = (int)$resource->getId();
		if (empty($resourceId)) {
			return false;
		}

		$module = $resource->getModule();
		$controller = $resource->getController();
		$action = $resource->getAction();
		
		$groupId = $this->getGroupId();
		$userId = $this->getId();

		!empty($groupId) || $groupId = Default_Model_UserGroup::GUESTS_ID;
		!empty($userId) || $userId = self::GUEST_ID;

		if (self::ADMINISTRATOR_ID == $userId) {
			return true;
		}

		$groupAclAlias = 'Group_' . $groupId;
		$userAclAlias = 'User_' . $userId;

		$acl = new Zend_Acl();
		$acl->addRole(new Zend_Acl_Role($groupAclAlias));
		$acl->addRole(new Zend_Acl_Role($userAclAlias), $groupAclAlias);
		
		//$acl->addResource(new Zend_Acl_Resource($module));
		//$acl->addResource(new Zend_Acl_Resource($controller), $module);
		//$acl->addResource(new Zend_Acl_Resource($action), $controller);
		//$acl->addResource(new Zend_Acl_Resource('index'));
		$acl->addResource(new Zend_Acl_Resource('auth'));
		$acl->addResource(new Zend_Acl_Resource($controller));

		$acl->deny();
		$acl->allow(null, 'auth', array('login', 'logout'));

		$groupAclTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_UserGroup_Acl');
		$resourceAcl = $groupAclTable->getGroupRights($groupId, $resourceId);
		if (!empty($resourceAcl)) {
			$acl->allow($groupAclAlias, $controller, array($action, 'index'));
		}

		return $acl->isAllowed($userAclAlias, $controller, $action);;
	}

	/**
	 * Create the salt string
	 *
	 * @return string
	 */
	private static function createSalt()
	{
		$salt = '';
		for ($i = 0; $i < 50; $i++) {
			$salt .= chr(rand(33, 126));
		}
		return $salt;
	}

	public function getUserGroup()
	{
		$group = new Default_Model_UserGroup();
		return $group->loadById($this->getGroupId());
	}

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

	public function setPassword($password, $encode = false)
	{
		if ($encode) {
			$this->_password = self::getEncodedPassword($password);
		} else {
			$this->_password = $password;
		}
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
