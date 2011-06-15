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
 * @version    $Id: BrandsController.php 283 2010-03-10 22:26:43Z TPavuk $
 */

class Catalog_PostsController extends KIT_Controller_Action_Backend_Abstract
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
		$postsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Post');
		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($postsTable));
		} else {
			$productsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
			$this->view->prods = $productsTable->getSelectedFields(
				array(
					$productsTable->getPrimary(),
					$productsTable->getFieldByAlias('alias')
				),
				null,
				true
			);
		}
	}

	public function addAction()
	{
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
			$this->_redirect('catalog/posts/add');
		}

		$this->_editForm($id);
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Post')
		);
	}
}

