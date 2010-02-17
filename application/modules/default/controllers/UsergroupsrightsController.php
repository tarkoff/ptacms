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

class Default_UsersGroupsRightsController extends KIT_Controller_Action_Backend_Abstract
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
			$viewParams = array('page' => $this->_getParam('page', 1),
								'rows' => $this->_getParam('rows', 20),
						   'sortField' => $this->_getParam('sidx'));
			$filterParams = array('searchField' => $this->_getParam('searchField'),
								 'searchString' => $this->_getParam('searchString'),
								   'searchOper' => $this->_getParam('searchOper'));
			$users = $usersTable->getView($viewParams, $filterParams);
			$primaryField = $usersTable->getPrimary();
			$jsonUsersList = array();
			foreach ($users->rows as $user) {
				$jsonUsersList[] = array(
					'id' => $user[$primaryField],
					'cell' => array_values($user)
				);
			}
			$users->rows = $jsonUsersList;
			$this->_helper->json($users);
		} else {
			$userGroupsTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_UserGroup');
			$this->view->userGroups = $userGroupsTable->getSelectedFields();
			$this->view->userStatuses = Default_Model_User::getUserStatuses();
		}
	}

	function addAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		$this->view->title = 'User Add Form';
		$this->view->headTitle($this->view->title, 'PREPEND');

		$form = new Default_Form_Users_Edit();
		$this->view->form = $form;

		if ($form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('users/list');
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}
	}

	function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$this->view->title = 'User Edit Form';
		$this->view->headTitle($this->view->title, 'APPEND');

		$id = (int)$this->_getParam('id', 0);
		$form = new Default_Form_Users_Edit($id);
		$this->view->form = $form;

		if ($form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect('users/list');
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}
	}
	
	public function deleteAction()
	{
		$id = (int)$this->_getParam('id', 0);
		$userTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_User');

		$deleted = false;
		if (!empty($id)) {
			$deleted = $userTable->removeById($id);
		}

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($deleted);
		}
	}

	public function loginAction()
	{
		
	}
}

