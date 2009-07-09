<?php
/**
 * Field Group edit form
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class FieldsGroups_editForm extends PTA_Control_Form 
{
	private $_fieldGroup;
	private $_copy;

	public function __construct($prefix, $brand, $copy = false)
	{
		$this->_fieldGroup = $brand;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Field Group Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Group Title', true, '');
		$title->setSortOrder(100);
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Text('alias', 'Group Alias', true, '');
		$alias->setSortOrder(200);
		$this->addVisual($alias);

		$catsTable = PTA_DB_Table::get('Catalog_Category');
		$category = new PTA_Control_Form_Select(
			'categoryId', 'Category', false,
			$catsTable->getSelectedFields(
				array('id', 'title'),
				$catsTable->getFieldByAlias('parentId') . ' <> 0'
			), 
			$this->_fieldGroup->getCategoryId()
		);
		$category->setSortOrder(201);
		//$category->setMultiple(true);
		$this->addVisual($category);

		$sortOrder = new PTA_Control_Form_Text('sortOrder', 'Category Order', false, '0');
		$sortOrder->setSortOrder(202);
		$this->addVisual($sortOrder);
		
		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_fieldGroup->loadTo($data);
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

		$this->_fieldGroup->loadFrom($data);

		if ($this->_copy) {
			$this->_fieldGroup->setId(null);
		} else {
			$groupTable = $this->_fieldGroup->getTable();
			$existedGroup = current(
				$groupTable->findByFields(
					array('alias'), array($data->alias)
				)
			);
			$groupIdField = $groupTable->getPrimary();
			if (
				!empty($existedGroup)
				&& $this->_fieldGroup->getId() != $existedGroup[$groupIdField]
			) {
				return false;
			}
		}

		if ($this->_fieldGroup->save() || $this->_copy) {
			$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}

		return true;
	}
}
