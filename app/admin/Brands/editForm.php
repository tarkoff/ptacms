<?php
/**
 * Catalog Brand edit form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Brands_editForm extends PTA_Control_Form 
{
	private $_brand;
	private $_copy;

	public function __construct($prefix, $brand, $copy = false)
	{
		$this->_brand = $brand;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Brand "' . $brand->getTitle() . '" Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Brand Title', true, '');
		$title->setSortOrder(100);
		$title->setCssClass('textField');
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Text('alias', 'Brand Alias', true, '');
		$alias->setSortOrder(200);
		$alias->setCssClass('textField');
		$this->addVisual($alias);

		$url = new PTA_Control_Form_Text('url', 'Brand Url', false, '');
		$url->setSortOrder(300);
		$url->setCssClass('textField');
		$this->addVisual($url);
		
		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_brand->loadTo($data);
		$data->submit = 'save';

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

		$this->_brand->loadFrom($data);

		if ($this->_copy) {
			$this->_brand->setId(null);
		}

		if ($this->_brand->save() || $this->_copy) {
			PTA_Util::createContentPath($this->_brand->getContentPhotoPath());
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Brand ' . $this->_brand->getTitle() . ' successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while ' . $this->_brand->getTitle() . ' brand saving!'
			);
		}

		return true;
	}
}
