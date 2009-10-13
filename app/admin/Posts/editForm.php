<?php
/**
 * Catalog Brand edit form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 108 2009-07-18 12:22:43Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Posts_editForm extends PTA_Control_Form 
{
	private $_post;
	private $_copy;

	public function __construct($prefix, $brand, $copy = false)
	{
		$this->_post = $brand;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Post Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('author', 'Author', true, '');
		$title->setSortOrder(100);
		$title->setCssClass('textField');
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Text('post', 'Post', true, '');
		$alias->setSortOrder(200);
		$alias->setCssClass('textField');
		$this->addVisual($alias);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save Post');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_post->loadTo($data);

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

		$this->_post->loadFrom($data);

		if ($this->_copy) {
			$this->_post->setId(null);
		}

		if ($this->_post->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Post successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while post saving!'
			);
		}

		return true;
	}
}
