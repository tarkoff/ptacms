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
 * @version    $Id: Action.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

class Default_Model_DbTable_User extends KIT_Db_Table_Abstract
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
		$this->setViewSelect(
			$this->getAdapter()->select()->from(
				$this->_name,
				array('USERS_ID', 'USERS_GROUPID', 'USERS_LOGIN', 'USERS_REGISTERDATE')
			)
		);
	}
}
