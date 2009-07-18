<?php
/**
 * User Edit Form
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Users_editForm extends PTA_Control_Form 
{
	private $_user;
	private $_copy;

	public function __construct($prefix, $user, $copy = false)
	{
		$this->_user = $user;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('User "' . $user->getLogin() .'" Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('login', 'User Login', true, '');
		$title->setSortOrder(100);
		$title->setCssClass('textField');
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Password('password', 'User Password', true, '');
		$alias->setSortOrder(200);
		$alias->setCssClass('textField');
		$this->addVisual($alias);
		
		$groups = PTA_DB_Table::get('UserGroup')->getSelectedFields(array('id', 'name'));
		$group = new PTA_Control_Form_Select('groupid', 'User Group',true, $groups);
		$group->setSortOrder(210);
		$this->addVisual($group);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_user->loadTo($data);
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

		$this->_user->loadFrom($data);

		if ($this->_copy) {
			$this->_user->setId(null);
		}

		$this->_user->setPassword(PTA_User::getPasswordHash($data->password));

		if ($this->_user->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'User Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl(), 3);
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
