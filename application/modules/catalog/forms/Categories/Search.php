<?php
/**
 * Catalog Category Search Form
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
 * @version    $Id$
 */

class Catalog_Form_Categories_Search extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Category
	 */
	private $_category;
	/**
	 * @var Zend_Db_Select
	 */
	private $_select;
	private $_fields = array();

	/**
	 * Constructor
	 *
	 * @param KIT_Catalog_Category $category
	 * @param Zend_Db_Select $select
	 * @param array $options
	 * @return void
	 */
	public function __construct($select = null, $options = null)
	{
		if ($select instanceof Zend_Db_Select) {
			$this->_select = $select;
		}

		parent::__construct($options);
		$this->setName('searchForm');
		$this->setMethod('GET');
		$this->setLegend('Search Form');

		$title = new Zend_Form_Element_Text('q');
		$title->setLabel('Query')
			  ->setRequired(false)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($title);



		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Search');
		$this->addElement($submit);
	}

	public function submit()
	{
		$formData = $this->getValidValues($_GET);
		if (isset($formData['submit'])) {
			unset($formData['submit']);
		}

		if (empty($formData)) {
			return false;
		} else {
			$this->applyFilter($formData);
		}

		$this->populate($formData);
		return true;
	}

	public function applyFilter($data = array())
	{
		!empty($data) || $data = $this->getValidValues($_GET);

		$productsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$brandsTable  = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Brand');

		$prodTitleField  = $productsTable->getFieldByAlias('title');
		$brandTitleField = $brandsTable->getFieldByAlias('title');

		if (!empty($data['q'])) {
			$select = $this->getSelect();
			$values = explode(' ', $data['q']);
			foreach ($values as $value) {
				if (!empty($value)) {
					$value = strtolower($value);
					$select->where(
						'LOWER(prods.' . $prodTitleField . ') LIKE "%' . $value . '%"'
						. ' OR '
						. 'LOWER(brands.' . $brandTitleField . ') LIKE "%' . $value . '%"'
					);
				}
			}
		}

	}

	/**
	 * Return Zend_Db_Select with applied search values
	 *
	 * @return void
	 */
	public function getSelect()
	{
		if (empty($this->_select)) {
			$productTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
			$this->_select = $productTable->getCatalogSelect();
		}
		return $this->_select;
	}
}
