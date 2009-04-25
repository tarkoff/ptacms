<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Fields.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class UserGroups extends PTA_WebModule
{
	private $_userGroup;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'UserGroups.tpl');

		$this->_userGroup = new PTA_UserGroup('currentUserGroup');
		$this->setModuleUrl(ADMINURL . '/UserGroups/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('userGroup');

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
			$this->_userGroup->loadById($itemId);
		}

		$editForm = new UserGroups_editForm('editForm', $this->_userGroup, $copy);
		$this->addVisual($editForm);
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_userGroup->getTable();

		$fields = $fieldTable->getFields();
		
		$view = new PTA_Control_View('userGroupsView', $this->_userGroup, array_values($fields));

		$this->addActions($view);
		$res = $view->exec();

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/UserGroup', 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/UserGroup', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/UserGroup', 'remove.png');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_userGroup->loadById($itemId);
		}

		$this->_userGroup->remove();

		$this->redirect($this->getModuleUrl());
	}

}
