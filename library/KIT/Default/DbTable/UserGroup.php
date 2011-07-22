<?php
/**
 * User Groups Database Table
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
 * @version    $Id: UserGroup.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Default_DbTable_UserGroup extends KIT_Db_Table_Abstract
{
	protected $_name = 'USERGROUPS';
	protected $_primary = 'USERGROUPS_ID';
	
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
				array('USERGROUPS_ID', 'USERGROUPS_TITLE')
			)
		);
	}

}
