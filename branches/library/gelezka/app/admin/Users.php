<?php
/**
 * Users Controller
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Users extends PTA_WebModule
{
	private $_user;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Users.tpl');

		$this->_user = new PTA_User('currentUser');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Users/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('User');

		switch (ucfirst($action)) {
			case 'Add':
					$this->editAction();
			break;

			case 'List':
					$this->listAction();
			break;

			case 'Edit':
					$this->editAction($item);
			break;

			case 'Delete':
				$this->deleteAction($item);
			break;

			case 'Copy':
				$this->editAction($item, true);
			break;

			default:
				$this->listAction();
		}
	}

	public function editAction($itemId = null, $copy = false)
	{
		$this->setVar('tplMode', 'edit');

		if (!empty($itemId)) {
			$this->_user->loadById($itemId);
		}

		$editForm = new Users_editForm('editForm', $this->_user, $copy);
		$this->addVisual($editForm);
	}

	public function listAction()
	{
		$this->addVisual(new Common_FilterForm('Common_FilterForm'));

		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_user->getTable();

		$fields = $fieldTable->getFields();
		
		$view = new PTA_Control_View('userrsView', $this->_user, array_values($fields));

		if (($filter = $this->getFilterData())) {
			$view->setFilter($filter);
		}

		$this->addActions($view);
		$res = $view->exec();

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New User', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/User', 'Edit');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/User', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/User', 'Delete');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_user->loadById($itemId);
		}

		$this->_user->remove();

		$this->redirect($this->getModuleUrl());
	}

}
