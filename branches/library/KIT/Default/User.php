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

class KIT_Default_User extends KIT_Model_Abstract implements Zend_Acl_Role_Interface
{
	protected $_login;
	protected $_password;
	protected $_firstName;
	protected $_lastName;
	protected $_groupId;
	protected $_registered;
	protected $_email;
	protected $_status;
	protected $_aclRoleId;

	const STATUS_SUSPENDED = 0;
	const STATUS_ACTIVE = 1;

	const ADMINISTRATOR_ID = 1;
	const GUEST_ID = 2;

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

	public function getRoleId()
	{
		if (empty($this->_aclRoleId)) {
			return $this->getLogin();
		}

		return $this->_aclRoleId;
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
		$this->setOptions($authAdapter->getResultRowObject(), true);
		$auth->getStorage()->write($this);
		return true;
	}

	/**
	 * Check if user has rights for this resource
	 *
	 * @param int|KIT_Default_Resource $resource
	 * @return boolean
	 */
	public function hasRight($resource)
	{
		if (!($resource instanceof KIT_Default_Resource)) {
			$resourceId = intval($resource);
			$resource = new KIT_Default_Resource();
			$resource->loadById($resourceId);
		}

		$groupId = $this->getGroupId();
		$userId = $this->getId();

		!empty($groupId) || $groupId = KIT_Default_UserGroup::GUESTS_ID;
		!empty($userId) || $userId = self::GUEST_ID;

		if (self::ADMINISTRATOR_ID == $userId) {
			return true;
		}

		$resourceId = (int)$resource->getId();
		if (empty($resourceId)) {
			return false;
		}

		$module = $resource->getModule();
		$controller = $resource->getController();
		$action = $resource->getAction();

		$groupAclAlias = 'Group_' . $groupId;
		$userAclAlias = $this->getRoleId();

		$acl = new Zend_Acl();
		$acl->addRole(new Zend_Acl_Role($groupAclAlias));
		$acl->addRole(new Zend_Acl_Role($userAclAlias), $groupAclAlias);
		
		$acl->addResource(new Zend_Acl_Resource($controller));

		$acl->deny();
		//$acl->allow(null, null, array('index', 'login', 'logout'));

		$userAclTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_User_Acl');
		$userResourceAcl = $userAclTable->getUserResources($userId, $groupId, $resourceId);
		if (!empty($userResourceAcl)) {
			$acl->allow($userAclAlias, $controller, $action);
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
		$group = new KIT_Default_UserGroup();
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
