<?php
/**
 * Catalog Prices Controler
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brands.php 95 2009-07-12 19:14:37Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Prices extends PTA_WebModule
{
	private $_price;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Prices.tpl');

		$this->_price = PTA_DB_Object::get('Catalog_Price');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Prices/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Price');

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
			$this->_price->loadById($itemId);
		}

		$this->addVisual(new Prices_editForm('editForm', $this->_price, $copy));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');

		$productTable = PTA_DB_Table::get('Catalog_Product');
		$brandTable = PTA_DB_Table::get('Catalog_Brand');
		$currencyTable = PTA_DB_Table::get('Catalog_Currency');
		$priceTable = PTA_DB_Table::get('Catalog_Price');

		$fields = $priceTable->getFields();
		unset($fields['PRODUCTID'], $fields['CURRENCY']);

		$view = new PTA_Control_View('fieldsView', $this->_price, array_values($fields));
		
		$view->join(
			array('prod' => $productTable->getTableName()),
			'prod.' . $productTable->getPrimary() . ' = ' . $priceTable->getFieldByAlias('productId'),
			array(
				'PRODUCT_PRODUCT' => "CONCAT_WS(' ', brand." . $brandTable->getFieldByAlias('title')
				. ", prod." . $productTable->getFieldByAlias('title') . ")"
			)
		);

		$view->join(
			array('brand' => $brandTable->getTableName()),
			'prod.' . $productTable->getFieldByAlias('brandId') . ' = brand.' . $brandTable->getPrimary(),
			array()
		);

		$view->join(
			array('currency' => $currencyTable->getTableName()),
			$priceTable->getFieldByAlias('currency') . ' = currency.' . $currencyTable->getPrimary(),
			array('CURRENCY_CURRENCY' => $currencyTable->getFieldByAlias('title'))
		);

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Price', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Price', 'Edit');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Price', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Price', 'Delete');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_price->loadById($itemId);
		}

		if (!$this->_price->remove()) {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while theme delete!'
			);
		} else {
			$this->redirect($this->getModuleUrl());
		}
	}

}
