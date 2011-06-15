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

class KIT_Default_DbTable_User extends KIT_Db_Table_Abstract
{
	protected $_name = 'USERS';
	protected $_primary = 'USERS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$userGroupsTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_UserGroup');
		$select = $this->getAdapter()->select();

		$select->from(array('u' => $this->_name),
					  array('USERS_ID',
							'USERS_LOGIN',
							'USERS_PASSWORD',
							'USERS_FIRSTNAME',
							'USERS_LASTNAME',
							'USERS_EMAIL',
							'USERS_STATUS',
							'USERS_REGISTERED'));

		$select->join(array('ug' => $userGroupsTable->getTableName()),
					  'u.USERS_GROUPID = ug.USERGROUPS_ID',
					  array('USERGROUPS_TITLE'));

		$this->setViewSelect($select);
	}
}
