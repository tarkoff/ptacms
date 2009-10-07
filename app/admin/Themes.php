<?php
/**
 * Themes Controler
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brands.php 95 2009-07-12 19:14:37Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Themes extends PTA_WebModule
{
	private $_theme;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Themes.tpl');

		$this->_theme = PTA_DB_Object::get('Theme');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Themes/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Theme');

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
			$this->_theme->loadById($itemId);
		}

		$this->addVisual(new Themes_editForm('editForm', $this->_theme, $copy));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$view = new PTA_Control_View('fieldsView', $this->_theme);

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Theme', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Theme', 'Edit');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Theme', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Theme', 'Delete');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_theme->loadById($itemId);
		}

		if (!$this->_theme->remove()) {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while theme delete!'
			);
		} else {
			$this->redirect($this->getModuleUrl());
		}
	}

}
