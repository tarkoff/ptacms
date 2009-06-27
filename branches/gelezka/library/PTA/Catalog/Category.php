<?php
/**
 * Catalog Category
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category extends PTA_DB_Object
{

	private $_parentId;
	private $_alias;
	private $_title;
	private $_isPublic = 1;
	
	protected static $_tree;

	public function getParentId()
	{
		return $this->_parentId;
	}

	public function setParentId($value)
	{
		$this->_parentId = (int)$value;
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

	public function getIsPublic()
	{
		return $this->_isPublic;
	}

	public function setIsPublic($public)
	{
		$this->_isPublic = (boolean)$public;
	}
	
	/**
	 * Get Categories Tree
	 *
	 * @return PTA_Tree
	 */
	public static function getCategoriesTree()
	{
		if (empty(self::$_tree)) {
			$categoryTable = PTA_DB_Table::get('Catalog_Category');
			self::$_tree = new PTA_Tree('categoriesTree', $categoryTable->getAll());
			self::$_tree->setKeyField($categoryTable->getPrimary());
			self::$_tree->setParentKeyField($categoryTable->getFieldByAlias('parentId'));
			self::$_tree->buildTree();
		}
		
		return self::$_tree;
	}
}
