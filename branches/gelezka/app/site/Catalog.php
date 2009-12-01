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
	private $_searchData;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Products/View/Product');
	}

	public function init()
	{
		parent::init();

		//$this->addVisual(new Catalog_searchForm('searchForm'));

		$action = $this->getApp()->getAction();
		switch (ucfirst($action)) {
			case 'List':
				$this->listAction();
			break;
			case 'Search':
				//$this->listAction();
				$this->searchAction();
			break;
			case 'Filter':
				//$this->listAction();
				$this->fieldsFilterdAction();
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
		$catId = $this->getCategoryId();
		$this->setVar('view', $this->getCatalogPage($catId));
		$this->getApp()->setVar(
			'mixCategory',
			PTA_DB_Table::get('MixMarket_Category')->getMixCategoryId($catId)
		);
	}

	public function fieldsFilterdAction()
	{
		$this->setVar('tplAction', 'filter');
		if (!($filterData = $this->getHttpVar('Value'))) {
			return false;
		}

		$valuesTable = PTA_DB_Table::get('Catalog_Field_Value');
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');
		$categoryFieldsTable = PTA_DB_Table::get('Catalog_Category_Field');

		$fieldTitleField = $fieldsTable->getFieldByAlias('title');
		$valueField = $valuesTable->getFieldByAlias('value');
		$fieldIdField = $valuesTable->getFieldByAlias('fieldId');
		$categoryFieldIdField = $categoryFieldsTable->getPrimary();

		$select = $fieldsTable->select()->from(
			array('fields' => $fieldsTable->getTableName()),
			array($fieldTitleField)
		);

		$select->setIntegrityCheck(false);

		$select->join(
			array('values' => $valuesTable->getTableName()),
			'fields.' . $fieldsTable->getPrimary()
			. ' = values.' . $fieldIdField,
			array($fieldIdField, $valueField)
		);

		$select->join(
			array('catFields' => $categoryFieldsTable->getTableName()),
			'fields.' . $fieldsTable->getPrimary()
			. ' = catFields.' . $categoryFieldsTable->getFieldByAlias('fieldId'),
			array($categoryFieldIdField)
		);

		$select->where($valuesTable->getPrimary() . ' = ?', $filterData);
		//$valTitle = $valuesTable->findById($filterData);
		$res = array('valueId'=>$filterData);
		foreach ($fieldsTable->fetchAll($select)->toArray() as $field) {
			$res['fieldId'] = (int)$field[$fieldIdField];
			$res['categoryFieldId'][] = (int)$field[$categoryFieldIdField];
			$res['fieldTitle'] = $field[$fieldTitleField];
			$res['fieldValue'] = $field[$valueField];
		}

		$this->setFilterData($res);

		$this->getApp()->setActiveModule($this->getPrefix());
		$this->setVar('searchRequest', $res);
		$this->setVar('view', $this->getCatalogPage());
		
	}

	public function searchAction()
	{
		$this->setVar('tplAction', 'search');
		if (!($filterData = $this->getSearchData())) {
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
		//$categoryId = (array)intval($categoryId);
		$prodsTable = PTA_DB_Table::get('Catalog_Product');
		$prodCatsTable = PTA_DB_Table::get('PTA_Catalog_Product_Category');

		$subCategories = $this->getApp()->getModule('Categories')->getSubCategories($categoryId);
		if (!empty($subCategories)) {
			$categoryIdField = PTA_DB_Table::get('Catalog_Category')->getPrimary();
			$categoryId = array((int)$categoryId);
			foreach ($subCategories as $cat) {
				$categoryId[] = (int)$cat[$categoryIdField];
			}
		}

		$select = $prodsTable->getCatalogQuery($categoryId);
//		$select->where('prodCats.' . $prodCatsTable->getFieldByAlias('isDEfault') . ' = 1');
		$select->group('prods.' . $prodsTable->getPrimary());

		if (($searchData = $this->getSearchData())) {
			$brandTable = PTA_DB_Table::get('Catalog_Brand');

			$brandTitleField = $brandTable->getFieldByAlias('title');
			$productTitleField = $prodsTable->getFieldByAlias('title');

			$searchData = $this->quote($searchData);

			$select->having(
				'brands.' . $brandTitleField . ' like "%' . $searchData . '%"'
				. ' or prods.' . $productTitleField . ' like "%' . $searchData . '%"'
			);
		}
		
		if (($filterData = $this->getFilterData())) {
			$catFieldsTable = PTA_DB_Table::get('Catalog_Category_Field');
			$valuesTable = PTA_DB_Table::get('Catalog_Value');
			$select->join(
				array('fieldValues' => $valuesTable->getTableName()),
				'prods.' . $prodsTable->getPrimary()
				. ' = fieldValues.' . $valuesTable->getFieldByAlias('productId'),
				array()
			);
			$select->where('fieldValues.' . $valuesTable->getFieldByAlias('fieldId') . ' in (?)', $filterData['categoryFieldId']);
			$select->where('fieldValues.' . $valuesTable->getFieldByAlias('valueId') . ' = ?', $filterData['valueId']);
		}
		
		$select->order(array('prods.' . $prodsTable->getFieldByAlias('date') . ' DESC'));

		$view = new PTA_Control_View('catalogView');
		$view->setTable($prodCatsTable);
		$view->setSelect($select);
		$view->setMinRpp(10);
		$view->setMaxRpp(50);
		$view->setRpp(10);

		$prodsIds = array();
		$prodIdField = $prodsTable->getPrimary();
		$view = $view->exec();
		foreach ($view->data as $product) {
			$prodsIds[] = $product[$prodIdField];
		}

		$prodIdField = $prodCatsTable->getFieldByAlias('productId');
		foreach ($prodCatsTable->getDefaultCategory($prodsIds, false) as $prodCat) {
			$view->prodsDefaultCats[$prodCat[$prodIdField]] = $prodCat;
		}

		return $view;
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

	public function setSearchData($data)
	{
		$this->_searchData = $data;
	}
	
	public function getSearchData()
	{
		return $this->_searchData;
	}
}
