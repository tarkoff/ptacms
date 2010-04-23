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
 * @version    $Id: CategoriesController.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class Catalog_CategoryGroupsController extends KIT_Controller_Action_Backend_Abstract
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
		$this->view->catid = $this->_getParam('catid');
		$catsTable      = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
		$groupsTable    = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group');
		$catGroupsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category_Group');

		if ($this->getRequest()->isXmlHttpRequest()) {
			if (!empty($this->view->catid)) {
				$select = $catGroupsTable->getViewSelect();
				$select->where($catGroupsTable->getFieldByAlias('categoryId') . '=?', $this->view->catid);
				$catGroupsTable->setViewSelect($select);
			}
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

	public function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$id = (int)$this->_getParam('id', 0);
		$this->view->form = new Catalog_Form_CategoryGroups_Edit($id);
		if ($this->view->form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('catalog/categorygroups');
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			} else {
				//$this->_redirect('catalog/categories/fieldgroups');
			}
		}
	}

	public function fieldsAction()
	{
		$this->view->cgid = (int)$this->_getParam('cgid', 0);

		$fieldsTable      = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field');
		$groupFieldsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field');

		if (!empty($this->view->cgid)) {
			$select = $groupFieldsTable->getViewSelect();
			$select->where($groupFieldsTable->getFieldByAlias('categoryGroupId') . '=?', $this->view->cgid);
			$groupFieldsTable->setViewSelect($select);
		}
//Zend_Registry::get('logger')->err($select->assemble());
		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($groupFieldsTable));
		} else {
			$this->view->fields = $fieldsTable->getSelectedFields(
				array($fieldsTable->getPrimary(), $fieldsTable->getFieldByAlias('title')),
				null,
				true
			);
		}
	}

	public function fieldseditAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deletefieldAction();
			$this->_helper->json(1);
		}

		$cgid = (int)$this->_getParam('cgid', 0);
		$this->view->form = new Catalog_Form_CategoryGroups_Fields($cgid);
		if ($this->view->form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('catalog/categorygroups/fields/cgid/' . $cgid);
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			} else {
				//$this->_redirect('catalog/categories/fieldgroups');
			}
		}
	}

	public function deletefieldAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group_Field')
		);
	}
	
	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category_Group')
		);
	}

}

