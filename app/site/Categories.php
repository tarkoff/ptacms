<?php
/**
 * Catalog Categories Controller
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Categories extends PTA_WebModule
{
	private $_category;
	private $_categoryId;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Categories.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Catalog/List/Category');
	}

	public function init()
	{
		parent::init();

		$httpCategory = $this->getHttpCategory();
		$this->setHttpCategory($httpCategory);
		
		if (!empty($httpCategory)) {
			$category = $this->getCategory();
			$catTitle = PTA_DB_Table::get('Catalog_Category')->getFieldByAlias('title');
			$this->getApp()->addKeyword($category[$catTitle]);
			
			$this->viewCategoryActions($httpCategory);
		}
		$this->setVar('category', $this->getCategory());
	}

	public function setHttpCategory($alias)
	{
		$this->getApp()->setCookie('Category', $alias, 0);
		$this->setHttpVar('Category', $alias);
		$this->setVar('selected', $alias);
	}

	public function getHttpCategory()
	{
		return $this->getHttpVar('Category', false);
	}

	public function getCategory()
	{
		$httpCategory = $this->getHttpCategory();
		if (!empty($httpCategory) && is_null($this->_category)) {
			$categoryTable = PTA_DB_Table::get('Catalog_Category');
			$categoryIdField = $categoryTable->getPrimary();
		
			$this->_category = current($categoryTable->findByFields('alias', $httpCategory));
			$this->_categoryId = (empty($this->_category[$categoryIdField]) ? 0 : $this->_category[$categoryIdField]);
		}

		return $this->_category;
	}

	public function getCategoryId()
	{
		if (is_null($this->_categoryId)) {
			$this->getCategory();
		}
		return $this->_categoryId;
	}

	public function getCategoriesTree()
	{
		return PTA_Catalog_Category::getCategoriesTree()->getBrancheFrom(0);
	}

	public function viewCategoryActions()
	{
//		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');
//		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Category', 'edit.png');
	}
	
	public function getSubCategories($parentCategoryId, $withProdsCnt = false)
	{
		if (empty($parentCategoryId)) {
			return array();
		}

		$catsTable = PTA_DB_Table::get('Catalog_Category');
		
		$subCategories = $catsTable->getChildsById($parentCategoryId);
		$catIdField = $catsTable->getPrimary();

		$catsIds = array();
		foreach ($subCategories as $cat) {
			$catsIds[$cat[$catIdField]] = $cat[$catIdField];
		}
	
		if ($withProdsCnt && !empty($catsIds)) {
			$prodsCatsCnt = PTA_DB_Table::get('Catalog_Product_Category')
				->getCategoryProductsCnt($catsIds);
			if (!empty($prodsCatsCnt)) {
				foreach ($subCategories as &$cat) {
					if (isset($prodsCatsCnt[$cat[$catIdField]])) {
						$cat['PRODS_CNT'] = $prodsCatsCnt[$cat[$catIdField]];
					} else {
						$cat['PRODS_CNT'] = 0;
					}
				}
			}
		}

		return $subCategories;
	}

	public function getParentCategories($categoryId = null)
	{
		$categoryTable = PTA_DB_Table::get('Catalog_Category');
		if (!empty($categoryId)) {
			$parentCategories = $categoryTable->getRootCategory($categoryId, true);

			$categoriesTree = PTA_Tree::get('categoryTree_' . $categoryId);
			$categoriesTree->setData($parentCategories);
			$categoriesTree->setKeyField($categoryTable->getPrimary());
			$categoriesTree->setParentKeyField($categoryTable->getFieldByAlias('parentId'));
			$categoriesTree->buildTree();
			return $categoriesTree->getTree();
		}

		return array();
	}
}
