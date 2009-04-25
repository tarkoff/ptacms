<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2009 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: TemplateEngine.php 13 2009-02-28 14:47:29Z TPavuk $
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
