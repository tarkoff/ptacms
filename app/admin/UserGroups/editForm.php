<?php
/**
 * User Group Edit From
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class UserGroups_editForm extends PTA_Control_Form 
{
	private $_userGroup;
	private $_copy;

	public function __construct($prefix, $userGroup, $copy = false)
	{
		$this->_userGroup = $userGroup;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('User Group "' . $userGroup->getName() . '" Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('name', 'User Group Name', true, '');
		$title->setSortOrder(100);
		$title->setCssClass('textField');
		$this->addVisual($title);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_userGroup->loadTo($data);
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

		$this->_userGroup->loadFrom($data);

		if ($this->_copy) {
			$this->_userGroup->setId(null);
		}

		if ($this->_userGroup->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Field User Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While User Group Saving!'
			);
			return false;
		}

		return true;
	}
}
