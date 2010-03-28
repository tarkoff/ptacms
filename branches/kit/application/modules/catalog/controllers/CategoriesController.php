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

class Catalog_CategoriesController extends KIT_Controller_Action_Backend_Abstract
{
	public function init()
	{
		$this->_helper->contextSwitch()
			 ->addActionContext('list', 'json')
			 ->addActionContext('add', 'json')
			 ->addActionContext('edit', 'json')
			 ->addActionContext('delete', 'json')
			 ->addActionContext('fieldgroups', 'json')
			 ->addActionContext('fieldgroupsedit', 'json')
			 ->addActionContext('deletecategorygroup', 'json')
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

		$this->_editForm($id);
	}

	public function fieldgroupsAction()
	{
		$catsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category');
		$groupsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Group');
		$catGroupsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category_Group');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($catGroupsTable));
		} else {
			$this->view->cats = $catsTable->getSelectedFields(
				array(
					$catsTable->getPrimary(),
					$catsTable->getFieldByAlias('title')
				),
				null,
				true
			);
			$this->view->groups = $groupsTable->getSelectedFields(
				array(
					$groupsTable->getPrimary(),
					$groupsTable->getFieldByAlias('title')
				),
				null,
				true
			);
		}
	}

	public function fieldgroupseditAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deletecategorygroupAction();
		}

		$id = (int)$this->_getParam('id', 0);
		$this->view->form = new Catalog_Form_Categories_FieldGroups($id);
		if ($this->view->form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('catalog/categories/fieldgroups');
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			} else {
				//$this->_redirect('catalog/categories/fieldgroups');
			}
		}
	}
	
	public function deletecategorygroupAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category_Group')
		);
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Category')
		);
	}
}

