<?php
/**
 * Catalog Brands Controler
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brands.php 95 2009-07-12 19:14:37Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class AuthorTypes extends PTA_WebModule
{
	private $_authorType;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Brands.tpl');

		$this->_authorType = new PTA_Catalog_Brand('Brand');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Brands/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Brand');

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
			$this->_authorType->loadById($itemId);
		}

		$editForm = new Brands_editForm('editForm', $this->_authorType, $copy);
		$this->addVisual($editForm);
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_authorType->getTable();

		$fields = $fieldTable->getFields();
		unset($fields['URL']);
		
		$view = new PTA_Control_View('fieldsView', $this->_authorType, array_values($fields));

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Brand', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Brand', 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Brand', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Brand', 'remove.png');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_authorType->loadById($itemId);
		}

		$this->_authorType->remove();
		$this->redirect($this->getModuleUrl());
	}

}
