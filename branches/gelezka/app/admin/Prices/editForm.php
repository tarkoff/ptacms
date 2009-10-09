<?php
/**
 * Price Edit Form
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 134 2009-07-30 17:20:19Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Prices_editForm extends PTA_Control_Form 
{
	private $_price;
	private $_copy;

	public function __construct($prefix, PTA_Catalog_Price $price, $copy = false)
	{
		$this->_price = $price;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Price Edit Form');
	}

	public function initForm()
	{

		$productTable = PTA_DB_Table::get('Catalog_Product');
		$productIds = $productTable->getSelectedFields(
			array('id', 'title'),
			array($productTable->getPrimary() . ' = ?', $this->_price->getProductId())
		);
		$productId = new PTA_Control_Form_Select('productId', 'Product', true, $productIds);
		$productId->setSortOrder(100);
		$this->addVisual($productId);

		$users = PTA_DB_Table::get('User')->getSelectedFields(array('id', 'login'));
		$userId = new PTA_Control_Form_Select('userId', 'User', true, $users);
		$userId->setSortOrder(200);
		$this->addVisual($userId);

		$price = new PTA_Control_Form_Text('price', 'Price', true);
		$price->setSortOrder(300);
		$this->addVisual($price);

		$descr = new PTA_Control_Form_TextArea('descr', 'Description');
		$descr->setSortOrder(400);
		$this->addVisual($descr);

		$url = new PTA_Control_Form_Text('url', 'URL');
		$url->setSortOrder(500);
		$this->addVisual($url);

		$dateFrom = new PTA_Control_Form_Text('dateFrom', 'Date From');
		$dateFrom->setSortOrder(600);
		$this->addVisual($dateFrom);

		$dateTo = new PTA_Control_Form_Text('dateTo', 'Date To');
		$dateTo->setSortOrder(700);
		$this->addVisual($dateTo);

		$sites = PTA_DB_Table::get('Site')->getSelectedFields(array('id', 'title'));
		$site = new PTA_Control_Form_Select('siteId', 'Site',true, $sites);
		$site->setSortOrder(800);
		$this->addVisual($site);

		$currencies = PTA_DB_Table::get('Catalog_Currency')->getSelectedFields(array('id', 'title'));
		$currency = new PTA_Control_Form_Select('currency', 'Currency', true, $currencies);
		$currency->setSortOrder(900);
		$this->addVisual($currency);

		$submit = new PTA_Control_Form_Submit('submit', 'Save Price', true, 'Save Price');
		$submit->setSortOrder(1000);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_price->loadTo($data);
		//$data->submit = 'save';

		return $data;
	}

	public function onSubmit(&$data)
	{
		$invalidFields = $this->validate($data);
		if (!empty($invalidFields)) {
			foreach ($invalidFields as $field) {
				$this->message(
					PTA_Object::MESSAGE_ERROR,
					'Field "' . $field->getLabel() . '" is required!'
				);
			}

			return false;
		}

		$this->_price->loadFrom($data);

		if ($this->_copy) {
			$this->_price->setId(null);
		}

		if ($this->_price->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Price Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Price Saving!'
			);
			return false;
		}

		return true;
	}
}
