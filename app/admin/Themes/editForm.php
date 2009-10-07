<?php
/**
 * Theme Edit Form
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 134 2009-07-30 17:20:19Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Themes_editForm extends PTA_Control_Form 
{
	private $_theme;
	private $_copy;

	public function __construct($prefix, PTA_Theme $theme, $copy = false)
	{
		$this->_theme = $theme;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Theme Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Theme Title', true);
		$title->setSortOrder(100);
		$this->addVisual($title);

		$sites = PTA_DB_Table::get('Site')->getSelectedFields(array('id', 'title'));
		$site = new PTA_Control_Form_Select('siteId', 'Site',true, $sites);
		$site->setSortOrder(200);
		$this->addVisual($site);

		$active = new PTA_Control_Form_Checkbox('active', 'Active', false, '1');
		$active->setSortOrder(250);
		//$active->setDisabled(true);
		$this->addVisual($active);
		
		$submit = new PTA_Control_Form_Submit('submit', 'Save Theme', true, 'Save Theme');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_theme->loadTo($data);
		//$data->submit = 'save';

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

		$this->_theme->loadFrom($data);
		if (empty($data->active)) {
			$this->_theme->setActive(0);
		}

		if ($this->_copy) {
			$this->_theme->setId(null);
		}

		if ($this->_theme->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Theme Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Theme Saving!'
			);
			return false;
		}

		return true;
	}
}
