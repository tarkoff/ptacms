<?php
/**
 * MixMarket Category
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Category.php 68 2009-06-27 15:31:31Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Category extends PTA_DB_Object
{

	private $_pid;
	private $_title;

	public function getPid()
	{
		return $this->_pid;
	}

	public function setPid($value)
	{
		$this->_pid = (int)$value;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

}
