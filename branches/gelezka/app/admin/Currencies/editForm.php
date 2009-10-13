<?php
/**
 * User Edit Form
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 134 2009-07-30 17:20:19Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Currencies_editForm extends PTA_Control_Form 
{
	private $_currency;
	private $_copy;

	public function __construct($prefix, $user, $copy = false)
	{
		$this->_currency = $user;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Currency "' . $user->getTitle() .'" Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Currency Title', true);
		$title->setSortOrder(100);
		$this->addVisual($title);

		$reduction = new PTA_Control_Form_Text('reduction', 'Currency Reduction', true);
		$reduction->setSortOrder(200);
		$this->addVisual($reduction);

		$submit = new PTA_Control_Form_Submit('submit', 'Save Currency', true, 'Save Currency');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_currency->loadTo($data);

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

		$this->_currency->loadFrom($data);

		if ($this->_copy) {
			$this->_currency->setId(null);
		}

		if ($this->_currency->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'User Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While User Saving!'
			);
			return false;
		}

		return true;
	}
}
