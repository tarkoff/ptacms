<?php
/**
 * Controller For Catalog List Of Products
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog extends PTA_WebModule
{
	private $_filterData;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Products/View/Product');
	}

	public function init()
	{
		parent::init();

		$this->addVisual(new Catalog_searchForm('searchForm'));

		$action = $this->getApp()->getAction();
		switch (ucfirst($action)) {
			case 'List':
				$this->listAction();
			break;
			case 'Search':
				//$this->listAction();
				$this->searchAction();
			break;
		}

		$this->setVar('brandUrl', PTA_BASE_URL . '/Brands/View/Brand');
	}

	public function listAction()
	{
		$this->setVar('tplAction', 'list');
		if ($this->isActive()) {
			if (($category = $this->getCategory())) {
				$catTable = PTA_DB_Table::get('Catalog_Category');
				$catTileField = $catTable->getFieldByAlias('title');
				$this->getApp()->setTitle($category[$catTileField]);
				unset($catTable, $catTileField, $category);
			} else {
				$this->redirect('/');
			}
		}
		$this->setVar('view', $this->getCatalogPage($this->getCategoryId(), 1));
	}
	
	public function searchAction()
	{
		$this->setVar('tplAction', 'search');
		if (!($filterData = $this->getFilterData())) {
			return false;
		}

		$this->getApp()->setActiveModule($this->getPrefix());
		$this->setVar('searchRequest', $this->quote($filterData));
		$this->setVar('view', $this->getCatalogPage($this->getCategoryId()));
	}
	
	/**
	 * Get Current Category
	 *
	 * @return array
	 */
	public function getCategory()
	{
		return $this->getApp()->getModule('Categories')->getCategory();
	}
	
	public function getCategoryId()
	{
		return $this->getApp()->getModule('Categories')->getCategoryId();
	}
	
	public function getCatalogPage($categoryId = null, $page = 1)
	{
		$categoryId = (array)$categoryId;
		$prodsTable = PTA_DB_Table::get('Catalog_Product');
		
		$subCategories = $this->getApp()->getModule('Categories')->getSubCategories($categoryId);
		if (!empty($subCategories)) {
			$categoryIdField = PTA_DB_Table::get('Catalog_Category')->getPrimary();
			foreach ($subCategories as $cat) {
				$categoryId[] = (int)$cat[$categoryIdField];
			}
		}

		$select = $prodsTable->getCatalogQuery($categoryId);
		$select->group($prodsTable->getPrimary());

		if (($filterData = $this->getFilterData())) {
			$brandTable = PTA_DB_Table::get('Catalog_Brand');

			$brandTitleField = $brandTable->getFieldByAlias('title');
			$productTitleField = $prodsTable->getFieldByAlias('title');

			$filterData = $this->quote($filterData);

			$select->having(
				'brands.' . $brandTitleField . ' like "' . $filterData . '%"'
				. ' or prods.' . $productTitleField . ' like "' . $filterData . '%"'
			);
		}

		$prodCatsTable = PTA_DB_Table::get('PTA_Catalog_Product_Category');
		$view = new PTA_Control_View('catalogView');
		$view->setTable($prodCatsTable);
		$view->setSelect($select);
		if (!empty($categoryId)) {
			$view->setTotalRecordsCnt(
				$view->getTotalRecordsCnt(
					$prodCatsTable->getAdapter()->quoteInto(
						$prodCatsTable->getFieldByAlias('categoryId') . ' in (?)',
						$categoryId
					)
				)
			);
		}
		$view->setMinRpp(10);
		$view->setMaxRpp(50);
		return $view->exec();
		/*
		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limitPage(intval($page), 10);

		return $prodsTable->fetchAll($select)->toArray();
		*/
	}
	
	/**
	 * Get Most Recent Products List
	 *
	 * @param int $categoryId
	 */
	public function getMostRecent($categoryId = null)
	{
		$prodsTable = PTA_DB_Table::get('PTA_Catalog_Product');
		$select = $prodsTable->getCatalogQuery($categoryId);
		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limit(10);

		$this->setVar('brandUrl', PTA_BASE_URL . '/Brands/View/Brand');
		return $prodsTable->fetchAll($select)->toArray();
		
	}
	
	public function getSponsoredLinks()
	{
		
	}
	
	public function setFilterData($data)
	{
		$this->_filterData = $data;
	}
	
	public function getFilterData()
	{
		return $this->_filterData;
	}

}
