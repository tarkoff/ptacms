<?php
/**
 * Users Database Table
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

class Default_Model_DbTable_User_Acl extends KIT_Db_Table_Abstract
{
	protected $_name = 'USERS_ACL';
	protected $_primary = 'USERSACL_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$resourcesTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Resource');
		$select = $this->getAdapter()->select();

		$select->from(
			array('uacl' => $this->_name),
			array(
				'USERSACL_ID',
				'USERSACL_RESOURCEID',
				'USERSACL_USERID'
			)
		);

		$select->join(array(
			'rsc' => $resourcesTable->getTableName()),
			'uacl.USERSACL_RESOURCEID = rsc.RESOURCES_ID',
			array('RESOURCES_TITLE' => "CONCAT_WS('/', rsc.RESOURCES_MODULE, rsc.RESOURCES_CONTROLLER, rsc.RESOURCES_ACTION)")
		);

		$this->setViewSelect($select);
	}

	/**
	 * Get user permissions
	 *
	 * @param int $groupId
	 * @param mixed $resourceId
	 * @return mixed
	 */
	public function getUserRights($userId, $resourceId = null)
	{
		$userId = (int)$userId;
		$resourceId = (int)$resourceId;
		if (empty($userId)) {
			return array();
		}

		$select = $this->select();
		if (!empty($resourceId)) {
			$select->where('USERSACL_RESOURCEID = ' . $resourceId);
		}

		$select->where('USERSACL_USERID = ' . $userId);

		return $this->fetchAll($select)->toArray();
	}

	/**
	 * Get user denied resources
	 *
	 * @param int $userId
	 * @return mixed
	 */
	public function getDeniedResources($userId)
	{
		$userId = (int)$userId;
		if (empty($userId)) {
			return array();
		}

		$resourcesTable = self::get('Default_Model_DbTable_Resource');
		$select = $resourcesTable->select();
		$select->setIntegrityCheck(false);
		$select->from(
			array('src' => $resourcesTable->getTableName()),
			array(
				$resourcesTable->getPrimary(),
				'RESOURCES_TITLE' => "CONCAT_WS('/', src.RESOURCES_MODULE, src.RESOURCES_CONTROLLER, src.RESOURCES_ACTION)"
			)
		);
		$select->joinLeft(
			array('usr' => $this->getTableName()),
			'src.' . $resourcesTable->getPrimary() . ' = usr.USERSACL_RESOURCEID',
			array()
		);

		$select->where('usr.' . $this->_primary . ' is null');
		$select->orWhere('usr.USERSACL_USERID <> ' . $userId);

		return $this->fetchAll($select)->toArray();
	}

	/**
	 * Get user resources
	 *
	 * @param int $userId
	 * @param int $groupId
	 * @return mixed
	 */
	public function getUserResources($userId, $groupId = null, $resourceId = null)
	{
		$userId     = (int)$userId;
		$groupId    = (int)$groupId;
		$resourceId = (int)$resourceId;

		if (empty($userId)) {
			return array();
		}

		$resourcesTable = self::get('Default_Model_DbTable_Resource');
		$groupAclTable = self::get('Default_Model_DbTable_UserGroup_Acl');

		$resourcePrimaryField = $resourcesTable->getPrimary();

		$select = $resourcesTable->select()->setIntegrityCheck(false);
		$select->from(
			array('src' => $resourcesTable->getTableName()),
			array(
				$resourcePrimaryField,
				'RESOURCES_TITLE' => "CONCAT_WS('/', src.RESOURCES_MODULE, src.RESOURCES_CONTROLLER, src.RESOURCES_ACTION)"
			)
		);

		$select->joinLeft(
			array('usr' => $this->getTableName()),
			'src.' . $resourcePrimaryField . ' = usr.USERSACL_RESOURCEID',
			array()
		);
		$select->where('usr.USERSACL_USERID = ' . $userId);

		if (!empty($groupId)) {
			$select->joinLeft(
				array('ug' => $groupAclTable->getTableName()),
				'src.' . $resourcePrimaryField . ' = ug.GROUPSACL_RESOURCEID',
				array()
			);
			$select->orWhere('ug.GROUPSACL_GROUPID = ' . $groupId);
		}

		if (!empty($resourceId)) {
			$select->where('src.' . $resourcePrimaryField . ' = ' . $resourceId);
		}
		return $this->fetchAll($select)->toArray();
	}

	/**
	 * Save user permissions
	 *
	 * @param int $userId
	 * @param mixed $resources
	 * @param int $groupId
	 * @return boolean
	 */
	public function setUserRights($userId, $resources, $groupId = null)
	{
		$userId = (int)$userId;
		$groupId = (int)$groupId;
		$resources = (array)$resources;

		if (empty($userId)) {
			return false;
		}

		if (empty($groupId)) {
			$groupResources = array();
		} else {
			$groupAclTable = self::get('Default_Model_DbTable_UserGroup_Acl');
			foreach ($groupAclTable->getGroupResources($groupId) as $resource) {
				$groupResources[$resource['RESOURCES_ID']] = $resource['RESOURCES_TITLE'];
			}
		}

		$sql = 'INSERT IGNORE INTO ' . $this->getTableName() . ' (USERSACL_RESOURCEID, USERSACL_USERID) VALUES ';
		$sqlParts = array();
		foreach ($resources as $resourceId) {
			if (!isset($groupResources[$resourceId])) {
				$sqlParts[] = '('. intval($resourceId) . ', ' . $userId . ')';
			}
		}

		$this->delete('USERSACL_USERID = ' . $userId);
		if (!empty($sqlParts)) {
			return $this->getAdapter()->query($sql . implode(', ', $sqlParts));
		}
		return false;
	}
}
