<?php
/**
 * User Groups Controller
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

class Default_UserGroupsController extends KIT_Controller_Action_Backend_Abstract
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
		$userGroupsTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_UserGroup');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($userGroupsTable));
		} else {
			//$this->view->assign('view',$users);
		}
	}

	function addAction()
	{
		$this->view->title = 'User Group Add Form';
		$this->_editForm();
	}

	function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$id = (int)$this->_getParam('id', 0);
		if (empty($id) && !$isAjax) {
			$this->_redirect('usergroups/add');
		}

		$this->view->title = 'User Group Edit Form';
		$this->_editForm($id);
	}

	public function rightsAction()
	{
		$id = (int)$this->_getParam('id', 0);
		if (empty($id)) {
			$this->_redirect('usergroups/list');
		}
		$form = new Default_Form_Usergroups_Acl($id);
		$this->view->title = 'User Group Rights';
		$this->view->form = $form;

		if ($form->submit()) {
			$this->_redirect('usergroups/list');
		}
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Default_DbTable_UserGroup')
		);
	}
}

