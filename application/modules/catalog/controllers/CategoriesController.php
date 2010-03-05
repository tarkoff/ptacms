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
 * @version    $Id: ProductsController.php 273 2010-02-17 12:42:59Z TPavuk $
 */

class Catalog_CategoriesController extends KIT_Controller_Action_Backend_Abstract
{
	public function init()
	{
		$this->_helper->contextSwitch()
			 ->addActionContext('list', 'json')
			 ->addActionContext('add', 'json')
			 ->addActionContext('edit', 'json')
			 ->addActionContext('delete', 'json')
			 ->initContext();
	}

	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$catsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($catsTable));
		} else {
			$this->view->cats = (array)$catsTable->getParentSelectOptions(
				$catsTable->getPrimary(),
				$catsTable->getFieldByAlias('title')
			);
		}
	}

	public function addAction()
	{
		$this->view->title = 'Category Add Form';
		$this->_editForm();
	}

	public function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$id = (int)$this->_getParam('id', 0);
		if (empty($id) && !$isAjax) {
			$this->_redirect('catalog/categories/add');
		}

		$this->view->title = 'Cstegory Edit Form';
		$this->_editForm($id);
	}

	public function fieldsAction()
	{
		$id = (int)$this->_getParam('id', 0);
		$this->view->category = KIT_Model_Abstract::get('Catalog_Model_Category', $id);
		$this->view->form = new Catalog_Form_Categories_Fields($id);
		if ($this->view->form->submit()) {
			$this->_redirect('catalog/categories/list');
		}
	}
	
	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category')
		);
	}
}

