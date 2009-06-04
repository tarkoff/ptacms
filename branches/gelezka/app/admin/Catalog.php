<?php
/**
 * Controller For Catalog List Of Products
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog extends PTA_WebModule
{
	private $_product;
	
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->_product = new PTA_Catalog_Product('Product');

		$this->setModuleUrl(PTA_ADMIN_URL . '/Catalog/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$itemId = $this->getApp()->getHttpVar('Item');

		if (!empty($itemId)) {
			$this->_product->loadById($itemId);
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

			case 'EditPhotos':
					$this->editPhotosAction();
			break;

			case 'DeletePhoto':
					$this->deletePhotosAction();
			break;

			case 'Delete':
				$this->deleteAction();
			break;

			case 'Copy':
				$this->editAction(true);
			break;

			default:
				$this->listAction();
		}
	}

	public function editAction($copy = false)
	{
		$this->setVar('tplMode', 'edit');
		$this->addVisual(new Catalog_editForm('editForm', $this->_product, $copy));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$catalogTable = $this->_product->getTable();

		$catalog = $catalogTable->getFields();
		unset($catalog['CATEGORYID'], $catalog['MANUFACTURERID']);

		$view = new PTA_Control_View('catalogView', $this->_product, array_values($catalog));
		$categoryTable = PTA_DB_Table::get('Catalog_Category');

		$view->join(
			$categoryTable->getTableName(), 
			($catalogTable->getFullFieldName('CATEGORYID') . ' = ' . $categoryTable->getFullPrimary()), 
			array($catalogTable->getTableName() . '_CATEGORY' => $categoryTable->getFieldByAlias('TITLE'))
		);

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Item', 'edit.png');
		$view->addCommonAction('Edit Photos', $this->getModuleUrl() . 'EditPhotos/Item', 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Item', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Item', 'remove.png');
	}

	public function deleteAction()
	{
		$this->_product->remove();
		$this->redirect($this->getModuleUrl());
	}

	public function editPhotosAction()
	{
		$this->setVar('tplMode', 'editPhotos');
		
		$form = new Catalog_EditPhotosForm('EditPhotosForm', $this->_product);
		$form->setVar('actionUrl', $this->getModuleUrl() . 'DeletePhoto/PhotoId/');
		$this->addVisual($form);
	}
	
	public function deletePhotosAction()
	{
		$photoId = $this->getApp()->getHttpVar('PhotoId');
		$productId = 0;
		
		$photo = PTA_DB_Object::get('Catalog_Product_Photo', intval($photoId));
		if ($photo->getId()) {
			$productId = $photo->getProductId();
			$photo->remove();
		}
		if (!empty($productId)) {
			$this->redirect($this->getModuleUrl() . 'EditPhotos/Item/' . $productId);
		}
	}
}
