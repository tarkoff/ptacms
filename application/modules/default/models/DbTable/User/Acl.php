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
		$resourcesTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Resource');
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
			array('RESOURCES_TITLE' => "CONCAT_WS('/', rsc.RESOURCES_MODULE, rsc.RESOURCES_CONTROLLER, rsc.RESOURCES_ACTION)")
		);

		$this->setViewSelect($select);
	}
}
