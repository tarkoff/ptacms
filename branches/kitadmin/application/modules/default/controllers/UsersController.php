<?php
/**
 * User Controller
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

class Default_UsersController extends KIT_Controller_Action_Backend_Abstract
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
		$usersTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_User');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($usersTable));
		} else {
			$userGroupsTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_UserGroup');
			$this->view->userGroups = $userGroupsTable->getSelectedFields();
			$this->view->userStatuses = Default_Model_User::getUserStatuses();
		}
	}

	public function addAction()
	{
		$this->view->title = 'User Add Form';
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
			$this->_redirect('users/add');
		}

		$this->view->title = 'User Edit Form';
		$this->_editForm($id);
	}

	public function rightsAction()
	{
		$id = (int)$this->_getParam('id', 0);
		if (empty($id)) {
			$this->_redirect('users/list');
		}
		$form = new Default_Form_Users_Acl($id);
		$this->view->title = 'User Rights';
		$this->view->form = $form;

		if ($form->submit()) {
			$this->_redirect('users/list');
		}
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('Default_Model_DbTable_User')
		);
	}

}
