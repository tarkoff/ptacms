<?php
/**
 *Catalog Category Edit Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Categories_editForm extends PTA_Control_Form 
{
	private $_category;

	public function __construct($prefix, $category)
	{
		$this->_category = $category;
		parent::__construct($prefix);

		$this->setTitle('Category ' . $category->getTitle() . ' Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Category Title', true, '');
		
		$title->setSortOrder(100);
		$this->addVisual($title);
		$title->setCssClass('textField');

		$CategoriesArray = $this->_category->getAll();
		$values = array(array(0 , 'Empty'));
		
		if (!empty($CategoriesArray)) {
			foreach ($CategoriesArray as $category) {
				if ($category->getId() == $this->_category->getId()) {
					continue;
				}
				$values[] = array($category->getId(), $category->getTitle());
			}
		}

		$alias = new PTA_Control_Form_Text('alias', 'Alias', true);
		$alias->setSortOrder(150);
		$this->addVisual($alias);

		$categorys = new PTA_Control_Form_Select('parentid', 'Parent Category', false, $values);
		$categorys->setSortOrder(200);
		$categorys->setSelected(2);
		$categorys->setCssClass('textField');
		$this->addVisual($categorys);
		
		$public = new PTA_Control_Form_Checkbox('ispublic', 'Is Public', false, 'on');
		$public->setChecked(false);
		$public->setSortOrder(250);
		$this->addVisual($public);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();
		$this->_category->loadTo($data);
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

		$this->_category->loadFrom($data);

		if ($this->_category->save()) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Category Successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Category Saving!'
			);
		}
	}

}
