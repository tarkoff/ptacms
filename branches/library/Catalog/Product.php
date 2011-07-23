<?php
/**
 * Product Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Product.php 407 2010-05-30 16:03:08Z TPavuk $
 */

class KIT_Catalog_Product extends KIT_Model_Abstract
{
	private $_title;
	private $_brandId;
	private $_alias;
	private $_shortDescr;
	private $_date;
	private $_url;
	private $_driversUrl;
	private $_authorId;
	/**
	 * @var KIT_Catalog_Product_Category
	 */
	private $_category;
	/**
	 * @var KIT_Catalog_Product_Castom_Fields
	 */
	private $_customFields;

	public function setId($id)
	{
		$this->_id = $id;
		$this->getCategory()->setProductId($this->_id);
		$this->getCustomFields()->setProductId($this->_id);
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function setUrl($url)
	{
		$this->_url = $url;
	}

	public function getDriversUrl()
	{
		return $this->_driversUrl;
	}

	public function setDriversUrl($url)
	{
		$this->_driversUrl = $url;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getAlias()
	{
		return $this->_alias;
	}

	public function setAlias($alias)
	{
		$this->_alias = $alias;
	}

	public function getShortDescr()
	{
		return $this->_shortDescr;
	}

	public function setShortDescr($descr)
	{
		$this->_shortDescr = $descr;
	}

	public function getBrandId()
	{
		return $this->_brandId;
	}

	public function setBrandId($id)
	{
		$this->_brandId = (int)$id;
	}

	public function getDate()
	{
		return $this->_date;
	}

	public function setDate($date)
	{
		$this->_date = $date;
	}

	public function getAuthorId()
	{
		return $this->_authorId;
	}

	public function setAuthorId($id)
	{
		$this->_authorId = (int)$id;
	}

	/**
	 * Get Default Product Category
	 *
	 * @return KIT_Catalog_Product_Category
	 */
	public function getCategory()
	{
		if (empty($this->_category)) {
			$productCategoryTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Category');
			$defaultCategory = $productCategoryTable->getDefaultCategory($this->getId(), true);
			$this->_category = self::get('KIT_Catalog_Product_Category');
			if (empty($defaultCategory)) {
				$this->_category->setProductId($this->getId());
				$this->_category->setCategoryId(0);
				$this->_category->setIsDefault(1);
			} else {
				$this->_category->setOptions(
					KIT_Db_Table_Abstract::dbFieldsToAlias($defaultCategory->toArray())
				);
			}
		}
		return $this->_category;
	}

	public function getCategoryId()
	{
		return $this->getCategory()->getCategoryId();
	}

	public function setCategoryId($id)
	{
		if ($this->getCategory()->getCategoryId() != $id) {
			$this->_customFields = null;
		}
		$this->getCategory()->setCategoryId($id);
	}

	/**
	 * Get Prodct Custom Fields
	 *
	 * @return KIT_Catalog_Product_Custom_Fields
	 */
	public function getCustomFields()
	{
		if (empty($this->_customFields)) {
			$this->_customFields = new KIT_Catalog_Product_Custom_Fields();
			$this->_customFields->setProductId($this->getId());
			$this->_customFields->setCategoryId($this->getCategoryId());
			$this->_customFields->build();
		}
		return $this->_customFields;
	}

	/**
	 * Set object options
	 *
	 * @param mixed $options
	 * @param boolean $isDbFields
	 * @return KIT_Model_Abstract
	 */
	public function setOptions($options, $isDbFields = false)
	{
		if ($isDbFields) {
			$options = KIT_Db_Table_Abstract::dbFieldsToAlias($options);
		}

		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			} else {
				$this->getCustomFields()->$method($value);
			}
		}
		return $this;
	}

	public function __call($method, $args)
	{
		$customFields = $this->getCustomFields();
		$alias = str_replace(array('set', 'get', '', $method));
		if ($customFields->has($alias)) {
			return call_user_func_array(array($customFields, $method), $args);
		}

		throw new Zend_Exception('Exception: ' . get_class($this) . "::{$method} unknown method called");
	}

	public function loadById($id)
	{
		parent::loadById($id);
		//$this->getCustomFields()->build();
		return $this;
	}

	/**
	 * Save data to database
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function save($data = null)
	{
		if (parent::save($data)) {
			$id = $this->getId();

			$category = $this->getCategory();
			$category->setProductId($id);
			$category->save();

			$cfields = $this->getCustomFields();
			$cfields->setProductId($id);
			$cfields->save();

			return true;
		}
		return false;
	}

	public function getProductPath($path = '.')
	{
		if (!empty($this->_alias)) {
			if (!empty($this->_brandId)) {
				$brand = self::get('KIT_Catalog_Brand', $this->_brandId);
				$path .= '/' . $brand->getAlias();
			}
			$path .= '/' . $this->getAlias();
		}

		if (!file_exists($path) && !mkdir($path, 0777, true)) {
			throw new Zend_Exception('Cannot create directory:' . $path);
		}
		return $path;
	}

}
