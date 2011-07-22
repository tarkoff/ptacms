<?php
/**
 *  PTA App User Group
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_UserGroup extends PTA_DB_Object 
{
	protected $_name;
	
	public function getName()
	{
		return $this->_name;
	}

	public function setName($groupName)
	{
		$this->_name = $groupName;
	}

}
