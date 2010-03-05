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
 * @version    $Id: User.php 273 2010-02-17 12:42:59Z TPavuk $
 */

class Catalog_Model_DbTable_Product extends KIT_Db_Table_Abstract
{
	protected $_name = 'PRODUCTS';
	protected $_primary = 'PRODUCTS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$userGroupsTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_UserGroup');
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
