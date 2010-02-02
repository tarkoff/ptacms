<?php
/**
 * Users Controller
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
 * @version    $Id: Action.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

class Default_UsersController extends KIT_Controller_Action
{
	public function init()
	{
		$this->_helper->contextSwitch()
			->addActionContext('list', 'json')
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
			$page = $this->_getParam('page');
			$rows = $this->_getParam('rows');
			$sortField = $this->_getParam('sidx');
			$sortDirection = $this->_getParam('sord');
			$users = $usersTable->getView($page, $rows, $sortField, $sortDirection);
			$jsonUsersList = array();
			foreach ($users->rows as $user) {
				$jsonUsersList[] = array(
					'id' => $user['USERS_ID'],
					'cell' => array_values($user)
				);
			}
			$users->rows = $jsonUsersList;
			$this->_helper->json($users);
		} else {
			//$this->view->assign('view',$users);
		}
	}
}

