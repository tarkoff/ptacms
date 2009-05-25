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

class Brands extends PTA_WebModule
{
	private $_brand;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Brands.tpl');

		$this->_brand = new PTA_Catalog_Brand('Brand');
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
			$this->_brand->loadById($itemId);
		}

		$editForm = new Brands_editForm('editForm', $this->_brand, $copy);
		$this->addVisual($editForm);
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_brand->getTable();

		$fields = $fieldTable->getFields();
		
		$view = new PTA_Control_View('fieldsView', $this->_brand, array_values($fields));

		$this->addActions($view);
		$res = $view->exec();

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Brand', 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Brand', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Brand', 'remove.png');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_brand->loadById($itemId);
		}

		$this->_brand->remove();

		$this->redirect($this->getModuleUrl());
	}

}
