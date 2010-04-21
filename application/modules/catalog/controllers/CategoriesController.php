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
 * @version    $Id: CategoriesController.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class Catalog_CategoriesController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$category = $this->_getParam('category');
		if (empty($category)) {
			$this->_redirect('/');
		}

		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$catsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');

		$select = $prodsTable->getCatalogSelect();
		$select->where('cats.' . $catsTable->getFieldByAlias('alias') . ' = ?', $category);
		$select->order(array('prods.' . $prodsTable->getFieldByAlias('alias') . ' ASC'));

		$adapter   = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);

		$paginator->setItemCountPerPage(12);
		$paginator->setPageRange(20);
		$paginator->setCurrentPageNumber((int)$this->_getParam('page', 1));

		$this->view->paginator = $paginator;
	}

}

