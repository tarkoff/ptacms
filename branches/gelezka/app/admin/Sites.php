<?php
/**
 * Sites Controler
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brands.php 95 2009-07-12 19:14:37Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Sites extends PTA_WebModule
{
	private $_site;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Sites.tpl');

		$this->_site = PTA_DB_Object::get('Site');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Sites/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Site');

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
			$this->_site->loadById($itemId);
		}

		$this->addVisual(new Sites_editForm('editForm', $this->_site, $copy));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$view = new PTA_Control_View('fieldsView', $this->_site);

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Site', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Site', 'Edit');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Site', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Site', 'Delete');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_site->loadById($itemId);
		}

		if (!$this->_site->remove()) {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while site delete!'
			);
		} else {
			$this->redirect($this->getModuleUrl());
		}
	}

}
