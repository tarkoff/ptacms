<?php
/**
 * Catalog Product Photos Upload Form
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
 * @version    $Id: Upload.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class Catalog_Form_Photos_Upload extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Product
	 */
	private $_protuct;

	/**
	 * @var KIT_Catalog_Product_Photo
	 */
	private $_photo;
	private $_photosPath;

	public function __construct($pid = 0, $options = null)
	{
		$pid = intval($pid);
		$this->_photosPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/images/catalog';

		$this->_protuct = KIT_Model_Abstract::get('KIT_Catalog_Product', $pid);
		$this->_photo   = KIT_Model_Abstract::get('KIT_Catalog_Product_Photo');
		
		parent::__construct($options);
		$this->setName('uploadForm');
		$this->setEnctype('multipart/form-data');
		$this->setLegend('Product Photos');

		$photo = new Zend_Form_Element_File('photo');
		$photo->setLabel('Upload an image:')
              ->setDestination($this->_protuct->getProductPath($this->_photosPath))
              ->setValueDisabled(true);
		$photo->addValidator('Count', false, 1);

		// limit to 200K
		$photo->addValidator('Size', false, 204800);
		// only JPEG, PNG, and GIFs
		$photo->addValidator('Extension', false, 'jpg,jpeg,png,gif');
		$this->addElement($photo);

		$this->addElement(new Zend_Form_Element_Submit('Upload'));
	}

	public function submit()
	{
		if ($this->isPost()) {
			if ($this->isValid($this->getValues()) && $this->photo->receive()) {
				$this->_photo->setProductId($this->_protuct->getId());
				$this->_photo->setIsDefault(true);
				$oldFileName = $this->photo->getFileName();
				$fileProperties = pathinfo($oldFileName);
				$newFileName = $fileProperties['dirname']
							   . '/' .  $this->_protuct->getAlias()
							   . '_' . substr(md5(date("r")), 0, 16)
							   . '.' . $fileProperties['extension'];
				
				if (rename($oldFileName, $newFileName)) {
					$fileName = str_replace($_SERVER['DOCUMENT_ROOT'], '', $newFileName);
				} else {
					$fileName = str_replace($_SERVER['DOCUMENT_ROOT'], '', $oldFileName);
				}
				$this->_photo->setFile($fileName);
				return $this->_photo->save();
			} else {
				$this->populate($this->getValues());
			}
		}
		return false;
	}
}
