<?php
/**
 * Menus Controller
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

class Default_MenusController extends KIT_Controller_Action_Backend_Abstract
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
		$menusTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Menu');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($menusTable));
		} else {
			$resourcesTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Resource');
			$this->view->menus = $menusTable->getMenusOptions();
			$this->view->resources = $resourcesTable->getResourcesOptions();
		}
	}

	public function addAction()
	{
		$this->view->title = 'Menu Item Add Form';
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
			$this->_redirect('menus/add');
		}

		$this->view->title = 'Menu Item Edit Form';
		$this->_editForm($id);
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Default_Model_DbTable_Menu')
		);
	}

}
