<?php
/**
 * Catalog Fields Controller
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

class Catalog_FieldsController extends KIT_Controller_Action_Backend_Abstract
{
	public function init()
	{
		$this->_helper->contextSwitch()
			 ->addActionContext('list', 'json')
			 ->addActionContext('add', 'json')
			 ->addActionContext('edit', 'json')
			 ->addActionContext('values', 'json')
			 ->addActionContext('valuesedit', 'json')
			 ->addActionContext('valuesdelete', 'json')
			 ->addActionContext('delete', 'json')
			 ->initContext();
	}

	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$fieldsTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($fieldsTable));
		} else {
			$this->view->fieldTypes = Catalog_Model_Field::getFieldTypes();
		}
	}

	public function addAction()
	{
		$this->view->title = 'Field Add Form';
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
			$this->_redirect('catalog/fields/add');
		}

		$this->view->title = 'Field Edit Form';
		$this->_editForm($id);
	}

	public function valuesAction()
	{
		$fid = (int)$this->_getParam('fid', 0);
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if (empty($fid)) {
			if ($isAjax) {
				$this->_helper->json(0);
			} else {
				$this->_redirect('catalog/fields/list');
			}
		}
		
		$fieldValuesTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field_Value');
		if ($isAjax) {
			$this->_helper->json($this->_getAjaxView($fieldValuesTable));
		} else {
			$this->view->fieldId = $fid;
		}
	}
	
	public function valuesaddAction()
	{
		$fid = (int)$this->_getParam('fid', 0);
		$id = (int)$this->_getParam('id', 0);
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if (empty($fid) && empty($id)) {
			if ($isAjax) {
				$this->_helper->json(0);
			} else {
				$this->_redirect('catalog/fields/list');
			}
		}
		
		$form = new Catalog_Form_Fields_Value($id, $fid);
		$this->view->form = $form;
		$this->view->headTitle($form->getLegend(), 'APPEND');

		if ($form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('catalog/fields/values');
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}
	}

	public function valueseditAction()
	{
		$this->valuesaddAction();
	}

	public function valuesdeleteAction()
	{
		$vid = (int)$this->_getParam('vid', 0);
		
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Field')
		);
	}
}

