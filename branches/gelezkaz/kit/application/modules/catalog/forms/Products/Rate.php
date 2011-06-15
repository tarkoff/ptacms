<?php
/**
 * Catalog Posts Edit Form
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

class Catalog_Form_Products_Rate extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Rate
	 */
	private $_rate;

	private $_protuctId;

	public function __construct($productId = 0, $options = null)
	{
		$productId = intval($productId);

		$this->_rate = KIT_Model_Abstract::get('KIT_Catalog_Rate');
		$this->_rate->setProductId($productId);

		$rateTable = $this->_rate->getDbTable();

		$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		$this->_rate->setIp(ip2long($ip));

		$data = $rateTable->fetchRow(
			$rateTable->getFieldByAlias('productId') . ' = ' . (int)$productId
			. ' AND ' . $rateTable->getFieldByAlias('ip') . ' = ' . $this->_rate->getIp()
		);

		if ($data instanceof Zend_Db_Table_Row_Abstract) {
			$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
			$this->_rate->setOptions($data);
		}

		parent::__construct($options);
		$this->setName('commentForm');

		$rating = new Zend_Form_Element_Select('rate');
		$rating->setLabel('Rate')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$rating->setOptions(
			array(
				1 => 'Very Bad',
				2 => 'Bad',
				3 => 'Normal',
				4 => 'Good',
				5 => 'Very Good'
			)
		);
		$this->addElement($rating);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		$this->addElement($submit);

	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			if ($this->isXmlHttpRequest()) {
				$newData = array();
				if (isset($formData['id']) && is_numeric($formData['id'])) {
					$newData['id'] = $formData['id'];
				} else {
					$newData['id'] = null;
				}
				foreach ($this->_rate->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldAlias])) {
						$newData[$fieldAlias] = $formData[$fieldAlias];
					}
				}
				$formData = $newData;
			}

			if (!empty($formData['rate'])) {
				$this->_rate->setRate($formData['rate']);
			}
			return $this->_rate->save();
		}
		return false;
	}
}
