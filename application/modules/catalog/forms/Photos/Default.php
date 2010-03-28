<?php
/**
 * Catalog Product default Photo Form
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

class Catalog_Form_Photos_Default extends KIT_Form_Abstract
{
	/**
	 * @var Catalog_Model_Product
	 */
	private $_protuct;

	/**
	 * @var Catalog_Model_Product_Photo
	 */
	private $_photo;
	private $_photosPath;

	public function __construct($pid = 0, $options = null)
	{
		$pid = intval($pid);
		$this->_photosPath = realpath(APPLICATION_PATH . '/../public/images/catalog');

		$this->_protuct = KIT_Model_Abstract::get('Catalog_Model_Product', $pid);
		$this->_photo   = KIT_Model_Abstract::get('Catalog_Model_Product_Photo');
		
		$photosTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product_Photo');

		parent::__construct($options);
		$this->setName('defaultForm');
		$this->setLegend('Default Product Photo');
//Zend_Registry::get('logger')->err($this->_protuct->getProductPath($this->_photosPath));

		$element = new Zend_Form_Element_Radio('isDefault');
		foreach ($photosTable->getProductPhotos($pid) as $photo) {
			$element->addMultiOption($photo->PHOTOS_ID, 'fff');
		}
		$this->addElement($element);

		$this->addElement(new Zend_Form_Element_Submit('submit'));
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			if ($this->isXmlHttpRequest()) {
			}
			if ($this->isValid($formData)) {
				$data = (object)$this->getValues();
				$this->_brand->setOptions($data);
				return $this->_brand->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
