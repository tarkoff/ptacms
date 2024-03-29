<?php
/**
 * Catalog Field Groups Controller
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
 * @version    $Id: FieldgroupsController.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class Catalog_FieldGroupsController extends KIT_Controller_Action_Backend_Abstract
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
		$fieldsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($fieldsTable));
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
			$this->_redirect('catalog/fieldgroups/add');
		}

		$this->_editForm($id);
	}
/*
	public function fieldsAction()
	{
		$id = (int)$this->_getParam('id', 0);
		$this->view->group = KIT_Model_Abstract::get('KIT_Catalog_Category_Group', $id);
		$this->view->form = new Catalog_Form_Fieldgroups_Fields($id);
		if ($this->view->form->submit()) {
			$this->_redirect('catalog/categories/fieldgroups');
		}
	}
*/
	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Field_Group')
		);
	}
}

