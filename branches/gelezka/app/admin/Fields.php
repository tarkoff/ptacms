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

		$editForm = new Fields_editFieldsValuesForm('editForm', $this->_field, $copy);
		$this->addVisual($editForm);
	}
	
	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_field->getTable();

		$fields = $fieldTable->getFields();
		
		$view = new PTA_Control_View('fieldsView', $this->_field, array_values($fields));

		$this->addActions($view);
		$res = $view->exec();

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Field', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Field', 'edit.png');
		$view->addCommonAction('Edit Field Values', $this->getModuleUrl() . 'EditFieldValues/Field', 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Field', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Field', 'remove.png');
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
