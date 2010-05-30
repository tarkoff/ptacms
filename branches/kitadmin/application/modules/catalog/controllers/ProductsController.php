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
		$usersTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($usersTable));
		} else {
		}
	}

	public function addAction()
	{
		$id = (int)$this->_getParam('id', 0);
		$catId = (int)$this->_getParam('catId', 0);
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		$this->view->catId = $catId;

		if (empty($catId)) {
			$catsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
			$this->view->cats = $catsTable->getSelectedFields(
				array(
					$catsTable->getPrimary(),
					$catsTable->getFieldByAlias('title')
				),
				null,
				true
			);
		} else {
			$form = new Catalog_Form_Products_Edit($id, $catId);
			$this->view->form = $form;
			$this->view->headTitle($form->getLegend(), 'APPEND');

			if ($form->submit()) {
				$this->_redirect('catalog/products/list');
			} else {
				$form->populate($form->getPost());
			}
		}
	}

	public function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$id = (int)$this->_getParam('id', 0);
		if (empty($id) && !$isAjax) {
			$this->_redirect('catalog/products/add');
		}
		$this->_editForm($id);
	}

	public function copyAction()
	{
		$this->_setParam('copy', 1);
		$this->_forward('edit');
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product')
		);
	}
}

