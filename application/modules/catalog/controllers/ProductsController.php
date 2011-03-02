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
 * @version    $Id$
 */

class Catalog_ProductsController extends KIT_Controller_Action_Backend_Abstract
{
	public function init()
	{
/*		$this->_helper->contextSwitch()
			 ->addActionContext('prices', 'html')
			 ->addActionContext('comments', 'html')
			 ->initContext();
*/
			 
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('prices', 'html')
                    ->addActionContext('comments', 'html')
                    ->addActionContext('newcomment', 'json')
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
		$brandsTable   = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Brand');
		$photosTable   = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Photo');
		$statsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Stat');

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
		
		$statsTable->updateStat($this->view->product->getId());
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
		
		$this->view->comments = $postsTable->findByFields(
			array('productId' => $this->view->product->getId())
		);
		
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
	
	public function pricesAction()
	{

		
	}
}
