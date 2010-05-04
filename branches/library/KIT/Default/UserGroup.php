<?php
/**
 * Menus Model
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

class KIT_Default_UserGroup extends KIT_Model_Abstract
{
	protected $_title;

	const ADMINISTRATORS_ID = 1;
	const GUESTS_ID = 2;

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getAcl()
	{
		if (empty($this->_id)) {
			$this->loadById(self::GUESTS_ID);
		}
		
		$acl = new Zend_Acl();

		$acl->addRole(new Zend_Acl_Role($this->getId()));
		
	}
}
