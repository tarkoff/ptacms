<?php
/**
 * Product Settings
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Stat.php 65 2009-06-04 21:30:33Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Product_Settings extends PTA_DB_Object
{

	private $_productId;
	private $_settings;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($value)
	{
		$this->_productId = (int)$value;
	}

	public function getSettings($serialized = true)
	{
		if (!$serialized && is_string($this->_settings)) {
			return @unserialize($this->_settings);
		} else {
			return $this->_settings;
		}
	}

	public function setSettings($settings)
	{
		if (!is_string($settings)) {
			$this->_settings = serialize($settings);
		} else {
			$this->_settings = $settings;
		}
	}

	public function getId()
	{
		return $this->getProductId();
	}

	public function setId($id)
	{
		$this->setProductId($id);
	}

}
