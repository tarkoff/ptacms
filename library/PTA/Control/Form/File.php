<?php
/**
 * Form Field File
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Control_Form_File extends PTA_Control_Form_Field 
{
	private $_uploader;
	public function init()
	{
		parent::init();
		$this->setFieldType(PTA_Control_Form_Field::TYPE_FILE);
//		$this->getForm()->setEnctype('multipart/form-data');
	}
	
	public function run()
	{
		parent::run();

		$this->getForm()->setEnctype('multipart/form-data');

		if ($this->getForm()->submitted()) {
			$this->upload();
		}
	}
	
	/**
	 * Get File Uploader
	 *
	 * @return Zend_File_Transfer_Adapter_Abstract
	 */
	public function getUploader()
	{
		if (empty($this->_uploader)) {
			$this->_uploader = new Zend_File_Transfer_Adapter_Http();
		}
		return $this->_uploader;
	}
	
	/**
	 * Set File Uploader Object
	 *
	 * @param Zend_File_Transfer_Adapter_Abstract $uploader
	 */
	public function setUploader(Zend_File_Transfer_Adapter_Abstract $uploader)
	{
		$this->_uploader = $uploader;
	}
	
	public function isImage($isImage = true)
	{
		if ($isImage) {
			$this->_uploader->addValidator('IsImage', false, 'jpg, jpeg,png,gif');
			$this->_uploader->addValidator('MimeType', false, 'image');
			$this->_uploader->addValidator('Extension', false, 'jpg, jpeg,png,gif');
/*
			$this->_uploader->addValidator('ImageSize', false,
									array(
										'minwidth' => 100, 'maxwidth' => 1400,
										'minheight' => 100, 'maxheight' => 1400
									)
							);
*/
		}
	}

	public function upload()
	{
		if (!$this->getUploader()->isValid()) {
			return false;
		}

		if (!$this->getUploader()->receive()) {
			$messages = $this->_uploader->getMessages();
			throw new PTA_Exception(implode("\n</br>", $messages));
		}
		$fileName = str_replace(PTA_ROOT_PATH, '', $this->_uploader->getFileName($this->getFormPrefix() . '_' . $this->getName()));
		if (!empty($fileName)) {
			$this->setValue(DIRECTORY_SEPARATOR . ltrim($fileName, DIRECTORY_SEPARATOR));
		}
		return $this->_uploader->isReceived();
	}

	public function isValid()
	{
		if (parent::isValid()) {
			if (!$this->getUploader()->isValid()) {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

}
