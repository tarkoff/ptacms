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
 * @version    $Id: Acl.php 278 2010-02-27 18:36:32Z TPavuk $
 */

class KIT_Default_DbTable_UserGroup_Acl extends KIT_Db_Table_Abstract
{
	protected $_name = 'USERGROUPS_ACL';
	protected $_primary = 'GROUPSACL_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$resourcesTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Resource');
		$select = $this->getAdapter()->select();

		$select->from(
			array('ugacl' => $this->_name),
			array(
				'GROUPSACL_ID',
				'GROUPSACL_RESOURCEID',
				'GROUPSACL_GROUPID'
			)
		);

		$select->join(array(
			'rsc' => $resourcesTable->getTableName()),
			'ugacl.GROUPSACL_RESOURCEID = rsc.RESOURCES_ID',
			array(
				'GROUPSACL_ID',
				'RESOURCES_TITLE' => "CONCAT_WS('/', rsc.RESOURCES_MODULE, rsc.RESOURCES_CONTROLLER, rsc.RESOURCES_ACTION)"
			)
		);

		$this->setViewSelect($select);
	}
	
	public function getGroupRights($groupId, $resourceId = null)
	{
		$groupId = (int)$groupId;
		$resourceId = (int)$resourceId;
		if (empty($groupId)) {
			return array();
		}

		$select = $this->select();
		if (!empty($resourceId)) {
			$select->where('GROUPSACL_RESOURCEID = ' . $resourceId);
		}

		$select->where('GROUPSACL_GROUPID = ' . $groupId);

		return $this->fetchAll($select)->toArray();
	}

	public function getDeniedResources($groupId)
	{
		$groupId = (int)$groupId;
		if (empty($groupId)) {
			return array();
		}

		$resourcesTable = self::get('KIT_Default_DbTable_Resource');
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
			array('gr' => $this->getTableName()),
			'src.' . $resourcesTable->getPrimary() . ' = gr.GROUPSACL_RESOURCEID',
			array()
		);

		$select->where('gr.' . $this->_primary . ' is null');
		$select->orWhere('gr.GROUPSACL_GROUPID <> ' . $groupId);

		return $this->fetchAll($select)->toArray();
	}

	public function getGroupResources($groupId)
	{
		$groupId = (int)$groupId;
		if (empty($groupId)) {
			return array();
		}

		$resourcesTable = self::get('KIT_Default_DbTable_Resource');
		$select = $resourcesTable->select();
		$select->setIntegrityCheck(false);
		$select->from(
			array('src' => $resourcesTable->getTableName()),
			array(
				$resourcesTable->getPrimary(),
				'RESOURCES_TITLE' => "CONCAT_WS('/', src.RESOURCES_MODULE, src.RESOURCES_CONTROLLER, src.RESOURCES_ACTION)"
			)
		);
		$select->join(
			array('gr' => $this->getTableName()),
			'src.' . $resourcesTable->getPrimary() . ' = gr.GROUPSACL_RESOURCEID',
			array()
		);

		$select->where('gr.GROUPSACL_GROUPID = ' . $groupId);

		return $this->fetchAll($select)->toArray();
	}

	public function setGroupRights($groupId, $resources)
	{
		$groupId = (int)$groupId;
		$resources = (array)$resources;

		if (empty($groupId)) {
			return false;
		}

		$sql = 'INSERT IGNORE INTO ' . $this->getTableName() . ' (GROUPSACL_RESOURCEID, GROUPSACL_GROUPID) VALUES ';
		$sqlParts = array();
		foreach ($resources as $resourceId) {
			$sqlParts[] = '('. intval($resourceId) . ', ' . $groupId . ')';
		}

		$this->delete('GROUPSACL_GROUPID = ' . $groupId);
		if (!empty($sqlParts)) {
			return $this->getAdapter()->query($sql . implode(', ', $sqlParts));
		}
		return false;
	}
}
