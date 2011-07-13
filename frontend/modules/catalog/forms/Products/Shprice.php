<?php
/**
 * Catalog Second Hand Price Edit Form
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Edit.php 304 2010-04-19 19:07:18Z TPavuk $
 */

class Catalog_Form_Products_Shprice extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Price
	 */
	private $_price;

	private $_protuctId;

	public function __construct($productId = 0, $options = null)
	{
		$productId = intval($productId);

		$this->_price = KIT_Model_Abstract::get('KIT_Catalog_Price');
		$this->_price->setProductId($productId);

		parent::__construct($options);
		$this->setName('shpricetForm');

		$title = new Zend_Form_Element_Text('author');
		$title->setLabel('Автор')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($title);

		$cost = new Zend_Form_Element_Text('cost');
		$cost->setLabel('Цена')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($cost);

		$currenciesTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Currency');
		$currency = new Zend_Form_Element_Select('currencyid');
		$currency->setLabel('Валюта')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$currency->addMultiOptions(
			$currenciesTable->getSelectedFields(
				array(
					$currenciesTable->getPrimarY(),
					$currenciesTable->getFieldByAlias('title')
				),
				null,
				true
			)
		);
		$this->addElement($currency);

		$actualTo = new Zend_Form_Element_Text('actualto');
		$actualTo->setLabel('Показывать обявление до:')
				 ->setAttrib('readonly', 'readonly')
				 ->setRequired(false)
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		$this->addElement($actualTo);

		$post = new Zend_Form_Element_Textarea('descr');
		$post->setLabel('Комментарий')
			  ->setRequired(false)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($post);

		$captcha = new Zend_Form_Element_Text('captcha');
		$captcha->setLabel('Для добавления комментария ответьте на вопрос: "Два сапога?"')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($captcha);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Отправить');
		$this->addElement($submit);

	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			$captcha = mb_strtolower(isset($formData['captcha']) ? $formData['captcha'] : '', 'UTF-8');
			if ($this->isXmlHttpRequest()) {
				$newData = array();
				if (isset($formData['id']) && is_numeric($formData['id'])) {
					$newData['id'] = $formData['id'];
				} else {
					$newData['id'] = null;
				}
				foreach ($this->_price->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldAlias])) {
						$newData[$fieldAlias] = $formData[$fieldAlias];
					}
				}
				$formData = $newData;
			}

			$validCaptcha = array('para', 'пара');
			$formData['captcha'] = $captcha;
			if ($this->isValid($formData) && (array_search($captcha, $validCaptcha) !== false)) {
				$this->_price->setOptions($this->getValues());
				return $this->_price->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
