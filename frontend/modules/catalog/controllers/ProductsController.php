<?php
/**
 * Products Controller
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
 * @version    $Id: ProductsController.php 457 2011-03-14 13:04:18Z TPavuk $
 */

class Catalog_ProductsController extends KIT_Controller_Action_Backend_Abstract
{

	const COMMENT_SAVED = true;

	public function init()
	{
/*		$this->_helper->contextSwitch()
			 ->addActionContext('prices', 'html')
			 ->addActionContext('comments', 'html')
			 ->initContext();
*/

		$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('prices', 'html')
        			->addActionContext('shprices', 'html')
                    ->addActionContext('comments', 'html')
                    ->addActionContext('newshprice', 'json')
                    ->addActionContext('newcomment', 'json')
                    ->addActionContext('rate', 'json')
                    ->initContext();
	}

	public function indexAction()
	{
		$this->_forward('view');
	}

	public function viewAction()
	{
		$productAlias = $this->_getParam('product');
		if (empty($productAlias)) {
			$this->_redirect('/');
		}

		$prodsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$catsTable     = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
		$prodCatsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Category');
		$brandsTable   = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Brand');
		$photosTable   = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Photo');
		$statsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Stat');
		$ratingsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Rating');
		$mixOffersTable = KIT_Db_Table_Abstract::get('KIT_MixMarket_DbTable_Offer');

		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			$this->_redirect('/');
		}

		$this->view->brand = KIT_Model_Abstract::get(
			'KIT_Catalog_Brand',
			$this->view->product->getBrandId()
		);

		$this->view->photos = $photosTable->fetchAll(
			$photosTable->getFieldByAlias('productId')
			. ' = ' . $this->view->product->getId()
		);

		$this->view->category = KIT_Model_Abstract::get(
			'KIT_Catalog_Category',
			$this->view->product->getCategoryId()
		);

		if ($this->view->category->getParentId()) {
			$this->view->parentCategory = KIT_Model_Abstract::get('KIT_Catalog_Category');
			$this->view->parentCategory->loadById($this->view->category->getParentId());
		}

		$this->view->categories = $prodCatsTable->getProductCategories($this->view->product->getId());
		$catIds = array($this->view->category->getId());
		$categoryIdField = $prodCatsTable->getFieldByAlias('categoryId');
		foreach ($this->view->categories as $category) {
			$catIds[] = $category->$categoryIdField;
		}
		$this->view->catsProdsCnt = $prodCatsTable->getCategoryProductsCount($catIds);

		$this->view->rating = KIT_Model_Abstract::get('KIT_Catalog_Rating');
		$this->view->rating->loadByFields(array('productId' => $this->view->product->getId()));
		$this->view->mixOffers = $mixOffersTable->getProductOffers($this->view->product->getID());

		$statsTable->updateStat($this->view->product->getId());
	}

	public function pricesAction()
	{
		$productAlias = $this->_getParam('product');
		if (empty($productAlias)) {
			//$this->_redirect('/');
			return false;
		}

		$mixOffersTable = KIT_Db_Table_Abstract::get('KIT_MixMarket_DbTable_Offer');
		$prodsTable     = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');

		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			//$this->_redirect('/');
			return false;
		}

		$this->view->mixOffers = $mixOffersTable->getProductOffers($this->view->product->getID());
	}

	public function shpricesAction()
	{
		$productAlias = $this->_getParam('product');
		$isAjax = $this->getRequest()->isXmlHttpRequest();

		if (empty($productAlias)) {
			if (!$isAjax) {
				$this->_redirect('/');
			} else {
				$this->_helper->html('err');
			}
		}

		$pricesTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Price');
		$prodsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');

		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			//$this->_redirect('/');
			return false;
		}

		$this->view->offers = $pricesTable->getPrices($this->view->product->getId(), false);

		$this->view->form = new Catalog_Form_Products_Shprice($this->view->product->getId());
		$this->view->form->setAction('/catalog/products/newshprice/product/'
									 . $this->view->product->getAlias() . '/format/json');
	}

	public function newshpriceAction()
	{
		$productAlias = strtolower($this->_getParam('product'));
		$isAjax = $this->getRequest()->isXmlHttpRequest();

		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			$this->_redirect('/');
		}

		$this->view->form = new Catalog_Form_Products_Shprice($this->view->product->getId());
		//$this->view->form->setAction('/catalog/products/comments/product/'
		//							 . $this->view->product->getAlias() . '/format/json');

		if ($this->view->form->submit()) {
			$this->view->err = !self::COMMENT_SAVED;
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('/catalog/products/view/product/' . $this->view->product->getAlias());
			}
		} else {
			$this->view->err = !self::COMMENT_SAVED;
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}
	}

	public function commentsAction()
	{
		$productAlias = $this->_getParam('product');
		$isAjax = $this->getRequest()->isXmlHttpRequest();

		if (empty($productAlias)) {
			if (!$isAjax) {
				$this->_redirect('/');
			} else {
				$this->_helper->html('err');
			}
		}

		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$postsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Post');

		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			$this->_redirect('/');
		}

		$this->view->comments = $postsTable->getPosts($this->view->product->getId(), false);

		$this->view->form = new Catalog_Form_Products_Comment($this->view->product->getId());
		$this->view->form->setAction('/catalog/products/newcomment/product/'
									 . $this->view->product->getAlias() . '/format/json');

	}

	public function newcommentAction()
	{
		$productAlias = strtolower($this->_getParam('product'));
		$isAjax = $this->getRequest()->isXmlHttpRequest();

		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			$this->_redirect('/');
		}

		$this->view->form = new Catalog_Form_Products_Comment($this->view->product->getId());
		//$this->view->form->setAction('/catalog/products/comments/product/'
		//							 . $this->view->product->getAlias() . '/format/json');

		if ($this->view->form->submit()) {
			$this->view->err = !self::COMMENT_SAVED;
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('/catalog/products/view/product/' . $this->view->product->getAlias());
			}
		} else {
			$this->view->err = !self::COMMENT_SAVED;
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}

	}

	public function rateAction()
	{
		$productAlias = strtolower($this->_getParam('product'));
		$isAjax = $this->getRequest()->isXmlHttpRequest();

		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$this->view->product = KIT_Model_Abstract::get('KIT_Catalog_Product');

		$data = $prodsTable->fetchRow(
			$prodsTable->getFieldByAlias('alias')
			. $prodsTable->getAdapter()->quoteInto(' = ?', $productAlias)
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->view->product->setOptions($data);
		} else {
			$this->_redirect('/');
		}

		$this->view->form = new Catalog_Form_Products_Rate($this->view->product->getId());
		//$this->view->form->setAction('/catalog/products/comments/product/'
		//							 . $this->view->product->getAlias() . '/format/json');

		if ($this->view->form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('/catalog/products/view/product/' . $this->view->product->getAlias());
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}
	}
}
