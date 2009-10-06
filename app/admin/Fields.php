<?php
/**
 * Catalog Brands Controller
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Fields extends PTA_WebModule
{
	private $_field;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Fields.tpl');

		$this->_field = new PTA_Catalog_Field('Field');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Fields/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Field');

		switch (ucfirst($action)) {
			case 'Add': 
					$this->editAction();
			break;

			case 'List':
					$this->listAction();
			break;

			case 'Edit':
					$this->editAction($item);
			break;

			case 'EditFieldValues':
					$this->editFieldValuesAction($item);
			break;
			
			case 'Delete':
				$this->deleteAction($item);
			break;

			case 'Copy':
				$this->editAction($item, true);
			break;

			default:
				$this->listAction();
		}
	}

	public function editAction($itemId = null, $copy = false)
	{
		$this->setVar('tplMode', 'edit');

		if (!empty($itemId)) {
			$this->_field->loadById($itemId);
		}

		$editForm = new Fields_editForm('editForm', $this->_field, $copy);
		$this->addVisual($editForm);
	}

	public function editFieldValuesAction($itemId = null, $copy = false)
	{
		$this->setVar('tplMode', 'EditFieldValues');

		if (!empty($itemId)) {
			$this->_field->loadById($itemId);
		}

		$editForm = new Fields_editFieldsValuesForm('fieldsValuesEditForm', $this->_field, $copy);
		$this->addVisual($editForm);
	}
	
	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_field->getTable();
		$fieldTypeField = $fieldTable->getFieldByAlias('fieldType');

		$app = $this->getApp();
		$fields = $fieldTable->getFields();
		
		if ($this->getApp()->ajaxMode()) {
			$view = new PTA_Control_View(
				'fieldsView', $this->_field, array_values($fields), PTA_Control_View::MODE_SIMPLEGRID
			);
		} else {
			$view = new PTA_Control_View(
				'fieldsView', $this->_field, array_values($fields)
			);
		}

		$this->addActions($view);
		
		$res = $view->exec($app->getHttpVar($app->getPrefix() . '_gridMode'));

		$fieldTypes = PTA_Control_Form_Field::getPossibleFields();

		foreach ($res->data as &$field) {
			$fieldTypeId = $field[$fieldTypeField];
			if (isset($fieldTypes[$fieldTypeId])) {
				$field[$fieldTypeField] = $fieldTypes[$fieldTypeId][1];
			} else {
				$field[$fieldTypeField] = 'Unknown';
			}
		}

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Field', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Field', 'Edit');
		$view->addCommonAction('Edit Field Values', $this->getModuleUrl() . 'EditFieldValues/Field', 'EditFieldValues');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Field', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Field', 'Delete');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_field->loadById($itemId);
		}

		$this->_field->remove();

		$this->redirect($this->getModuleUrl());
	}

}
