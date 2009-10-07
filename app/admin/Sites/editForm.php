<?php
/**
 * Site Edit Form
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 134 2009-07-30 17:20:19Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Sites_editForm extends PTA_Control_Form 
{
	private $_site;
	private $_copy;

	public function __construct($prefix, PTA_Site $site, $copy = false)
	{
		$this->_site = $site;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Site Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Site Name', true);
		$title->setSortOrder(100);
		$this->addVisual($title);

		$url = new PTA_Control_Form_Text('url', 'Site URL', true);
		$url->setSortOrder(200);
		$this->addVisual($url);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_site->loadTo($data);
		$data->submit = 'Save Site';

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

		$this->_site->loadFrom($data);

		if ($this->_copy) {
			$this->_site->setId(null);
		}

		if ($this->_site->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Site Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Site Saving!'
			);
			return false;
		}

		return true;
	}
}
