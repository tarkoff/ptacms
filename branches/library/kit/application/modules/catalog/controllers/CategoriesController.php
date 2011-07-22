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

class Catalog_CategoriesController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$category    = $this->_getParam('category');
		$subCategory = $this->_getParam('scat');
		if (empty($category)) {
			$this->_redirect('/');
		}

		$prodsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$catsTable   = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');

		$this->view->category = KIT_Model_Abstract::get('KIT_Catalog_Category');

		$data = $catsTable->fetchRow(
			$catsTable->getAdapter()->quoteInto(
				$catsTable->getFieldByAlias('alias') . ' = ?',
				$category
			)
		);
		$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
		$this->view->category->setOptions($data);

		if (empty($this->view->searchForm)) {
			$select = $prodsTable->getCatalogSelect();
		} else {
			$select = $this->view->searchForm->getSelect();
		}
		$select->where('cats.' . $catsTable->getPrimary() . ' = ?', $this->view->category->getId());
		$select->order(array('prods.' . $prodsTable->getFieldByAlias('alias') . ' ASC'));

		$this->view->form = new Catalog_Form_Categories_Filter($this->view->category, $select);
		$this->view->form->submit();

		$adapter   = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);

		$paginator->setItemCountPerPage(12);
		$paginator->setPageRange(20);
		$paginator->setCurrentPageNumber((int)$this->_getParam('page', 1));

		$this->view->paginator = $paginator;
	}

	public function searchAction()
	{
		$prodsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$catsTable   = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');

		if (empty($this->view->searchForm)) {
			$select = $prodsTable->getCatalogSelect();
		} else {
			$select = $this->view->searchForm->getSelect();
		}

		$select->order(array('prods.' . $prodsTable->getFieldByAlias('alias') . ' ASC'));

		$adapter   = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);

		$paginator->setItemCountPerPage(12);
		$paginator->setPageRange(20);
		$paginator->setCurrentPageNumber((int)$this->_getParam('page', 1));

		$this->view->paginator = $paginator;

	}

}

