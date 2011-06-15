<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: EditForm.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Prices_EditForm extends PTA_Control_Form 
{
	private $_price;

	public function __construct($prefix, PTA_Catalog_Price $price)
	{
		$this->_price = $price;

		parent::__construct($prefix);
		$this->setTitle('Price Edit Form');
	}

	public function initForm()
	{
		$currencyTable = PTA_DB_Table::get('Catalog_Currency');
		$currency = new PTA_Control_Form_Select('currency', 'Currency', true);
		$currency->setSortOrder(100);
		$currency->setOptionsFromArray(
			$currencyTable->getAll(),
			$currencyTable->getPrimary(),
			$currencyTable->getFieldByAlias('title')
		);
		$this->addVisual($currency);
		
		$price = new PTA_Control_Form_Text('price', 'Price', true, $this->_price->getPrice());
		$price->setSortOrder(150);
		$this->addVisual($price);

		$descr = new PTA_Control_Form_TextArea('descr', 'Description', true, $this->_price->getDescr());
		$descr->setSortOrder(200);
		$this->addVisual($descr);
/*
		$dateFrom = new PTA_Control_Form_Text('dateFrom', 'Date From', false);
		$dateFrom->setSortOrder(300);
		$this->addVisual($dateFrom);
*/
		$dateTo = new PTA_Control_Form_Text('dateTo', 'Date To', false);
		$dateTo->setSortOrder(350);
		$this->addVisual($dateTo);

		$captcha = new PTA_Control_Form_Text('captcha', 'Captcha', true);
		$captcha->setSortOrder(360);
		$this->addVisual($captcha);
		
		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();
		$data->password = '';
		return $data;
	}

	public function onSubmit(&$data)
	{
		$data->descr = $this->quote($data->descr);
		$captcha = (int)$data->captcha;
		if (
			4 != $captcha
			|| !$this->_price->getProductId() 
			|| !$this->_price->getUserId()
			|| empty($data->descr)
			|| empty($data->price)
		) {
			return self::FORM_ERROR_VALIDATE;
		}

		$this->_price->setCurrency(empty($data->currency) ? 1 : $data->currency);
		$this->_price->setPrice(empty($data->price) ? 0.01 : $data->price);
		$this->_price->setDescr($this->quote($data->descr));
		$this->_price->setDateTo(date("Y-m-d", strtotime($data->dateTo)));

		if (!$this->_price->save()) {
			return self::FORM_ERROR_SAVE;
		}

		return self::FORM_ERROR_NONE;
	}
}
