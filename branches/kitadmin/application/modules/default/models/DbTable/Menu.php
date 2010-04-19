<?php
/**
 * Resource Database Table
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

class Default_Model_DbTable_Menu extends KIT_Db_Table_Tree_Abstract
{
	protected $_name = 'MENUS';
	protected $_primary = 'MENUS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$resourceTable = self::get('Default_Model_DbTable_Resource');

		$select = $this->getAdapter()->select()->from(
			array('menu' => $this->_name),
			array('MENUS_ID', 'MENUS_TITLE', 'MENUS_ALIAS')
		);

		$select->joinLeft(
			array('menu2' => $this->_name),
			'menu.MENUS_PARENTID = menu2.' . $this->_primary,
			array('MENUS_PARENT' => 'IFNULL(menu2.MENUS_TITLE,"No Parent")')
		);

		$select->join(
			array('rsc' => $resourceTable->getTableName()),
			'menu.MENUS_RESOURCEID = rsc.' . $resourceTable->getPrimary(),
			array('RESOURCES_TITLE' => "CONCAT_WS('/', rsc.RESOURCES_MODULE, rsc.RESOURCES_CONTROLLER, rsc.RESOURCES_ACTION)")
		);

		$this->setViewSelect($select);
	}

	/**
	 * Get option for form select
	 *
	 * @return mixed
	 */
	public function getMenusOptions()
	{
		static $parentMenusOptions;

		if (!empty($parentMenusOptions)) {
			return $parentMenusOptions;
		}

		$parentMenusOptions = array(0 => 'No Parent');
		foreach ($this->fetchAll() as $menu) {
			$parentMenusOptions[$menu->MENUS_ID] = $menu->MENUS_TITLE;
		}

		return $parentMenusOptions;
	}

	/**
	 * Get user allowed menu items
	 *
	 * @param int $userId
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getAllowedMenu($userId = null, $groupId = null)
	{
		$userId = (int)$userId;
		$groupId = (int)$groupId;

		$select = $this->select()
					   ->from(array('menu' => $this->_name), $this->getFields(false))
					   ->order(array('MENUS_LEVEL', 'MENUS_LEFT'))
					   ->setIntegrityCheck(false);

		$resourceTable = self::get('Default_Model_DbTable_Resource');
		$select->join(
			array('rsc' => $resourceTable->getTableName()),
			'menu.MENUS_RESOURCEID = rsc.' . $resourceTable->getPrimary(),
			array('RESOURCES_MODULE', 'RESOURCES_CONTROLLER', 'RESOURCES_ACTION')
		);
		
		if (!empty($userId) && Default_Model_User::ADMINISTRATOR_ID != $userId) {
			$select->setIntegrityCheck(false);
			$userAclTable = self::get('Default_Model_DbTable_User_Acl');
			$select->joinLeft(
				array('uacl' => $userAclTable->getTableName()),
				'menu.MENUS_RESOURCEID = uacl.USERSACL_RESOURCEID',
				array()
			);
			$select->where('uacl.USERSACL_USERID = ' . $userId);
		}

		if (!empty($groupId) && Default_Model_UserGroup::ADMINISTRATORS_ID != $groupId) {
			$userGroupAclTable = self::get('Default_Model_DbTable_UserGroup_Acl');
			$select->joinLeft(
				array('ugacl' => $userGroupAclTable->getTableName()),
				'menu.MENUS_RESOURCEID = ugacl.GROUPSACL_RESOURCEID',
				array()
			);
			$select->oRwhere('ugacl.GROUPSACL_GROUPID = ' . $groupId);
		}

		return $this->fetchAll($select);
	}

	
}
