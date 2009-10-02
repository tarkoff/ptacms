<?php
/**
 * Product Currency
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Currency extends PTA_DB_Object
{

	private $_title;
	private $_reduction;

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getReduction()
	{
		return $this->_reduction;
	}

	public function setReduction($red)
	{
		$this->_reduction = $red;
	}
}
