<?php
/**
 * Catalog Brands Controler
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
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
		$this->addVisual(new Common_FilterForm('Common_FilterForm'));

		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_brand->getTable();

		$fields = $fieldTable->getFields();
		unset($fields['URL']);
		
		$view = new PTA_Control_View('fieldsView', $this->_brand, array_values($fields));

		if (($filter = $this->getFilterData())) {
			$view->setFilter($filter);
		}

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Brand', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Brand', 'Edit');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Brand', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Brand', 'Remove');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_brand->loadById($itemId);
		}

		PTA_Util::rmDir($this->_brand->getContentPhotoPath());
		$this->_brand->remove();
		
		$this->redirect($this->getModuleUrl());
	}

}
