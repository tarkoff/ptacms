<?php
/**
 * Sites Menus Controllers
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Menus extends PTA_WebModule
{
	private $_menu;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Menus.tpl');

		$this->setModuleUrl(PTA_ADMIN_URL . '/Menus/');
		$this->_menu = new PTA_Catalog_MenuItem('Category');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$itemId = $this->getApp()->getHttpVar('Category');
		$this->setVar('tplMode', 'list');

		if (!empty($itemId)) {
			$this->_menu->loadById($itemId);
		}

		switch (ucfirst($action)) {
			case 'Add': 
					$this->editAction();
			break;

			case 'List':
					$this->listAction();
			break;

			case 'Edit':
					$this->editAction();
			break;

			case 'Delete':
				$this->deleteAction();
			break;

			default:
				$this->listAction();
		}
	}

	public function editAction()
	{
		$this->setVar('tplMode', 'edit');

		$editForm = new Categories_editForm('editForm', $this->_menu);
		$this->addVisual($editForm);
	}

	public function listAction()
	{
		$view = new PTA_Control_View('categoriesView', $this->_menu);
		$this->addActions($view);

		$res = $view->exec();
		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Category', 'edit.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Category', 'remove.png');
	}

	public function deleteAction()
	{
		$this->_menu->remove();
		$this->redirect($this->getModuleUrl());
	}

}
