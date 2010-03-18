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
		$usersTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product');

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
			$catsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category');
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
				if ($isAjax) {
					$this->_helper->json(1);
				} else {
					$this->_redirect('catalog/products/list');
				}
			} else {
				if ($isAjax) {
					$this->_helper->json(0);
				}
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
			if ($isAjax) {
				$this->_helper->json(0);
			} else {
				$this->_redirect('catalog/products/add');
			}
		}
		$this->view->addScriptPath(APPLICATION_PATH . '/layouts/scripts/generic/');
var_dump($this->view->getScriptPaths());
		$this->_editForm($id);
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product')
		);
	}
}

