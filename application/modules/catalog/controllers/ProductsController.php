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
 * @version    $Id: ProductsController.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class Catalog_ProductsController extends KIT_Controller_Action_Backend_Abstract
{
	public function indexAction()
	{
		$this->_forward('list');
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
		//$prodCatsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product_Category');

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
	}
}
