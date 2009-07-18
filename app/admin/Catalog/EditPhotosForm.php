<?php
/**
 * Catalog Product Photos Form
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 63 2009-05-31 22:53:26Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Catalog_EditPhotosForm extends PTA_Control_Form 
{
	private $_product;
	private $_photosTable;
	private $_photos;
	private $_defaultPhoto;

	public function __construct($prefix, $product)
	{
		$this->_product = $product;
		$this->_photosTable = PTA_DB_Table::get('Catalog_Product_Photo');

		parent::__construct($prefix);

		$this->setTitle('Add Photos To "' . $this->_product->getTitle() . '" Product');
	}

	public function initForm()
	{
		$image = new PTA_Control_Form_File('photo', 'Photo');
		$image->setSortOrder(10);
		$image->getUploader()->setDestination(
			PTA_CONTENT_PHOTOS_PATH 
		);
		$image->isImage(true);
		$this->addVisual($image);

		$this->setVar('photos', $this->getPhotos());

		$this->addVisual(new PTA_Control_Form_Radio('default'));

		$submit = new PTA_Control_Form_Submit('submit', '', true, 'Upload Photo');
		$submit->setSortOrder(5000);
		$this->addVisual($submit);
	}

	public function onSubmit(&$data)
	{
		$photo = PTA_DB_Object::get('Catalog_Product_Photo');

		$brand = PTA_DB_Object::get('Catalog_Brand', $this->_product->getBrandId());
		if (($imgFile = PTA_Util::upload($brand->getContentPhotoPath()))) {
			$photo->setPhoto($imgFile);
			$photo->setProductId($this->_product->getId());
			if ($photo->save()) {
				$this->message(
					PTA_Object::MESSAGE_SUCCESS,
					'Photo successfully uploaded!'
				);
			} else {
				$this->message(
					PTA_Object::MESSAGE_ERROR,
					'Error while photo uploading!'
				);
			}
		
		}

		if (
			!empty($data->default)
			&& ($this->_product->getPhotoId() != $data->default)
		) {
			$this->_product->setPhotoId($data->default);
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Default photo for ' . $this->_product->getTitle() . ' successfully setted!'
			);
		}
		
		
/*
		if ($this->_product->save()) {
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl(), 3);
		}
*/
	}
	
	public function getPhotos()
	{
		if (is_null($this->_photos)) {
			$this->_photos = (array)$this->_photosTable->findByFields(
				array('productId'),
				array($this->_product->getId())
			);
		}

		return $this->_photos;
	}

}
