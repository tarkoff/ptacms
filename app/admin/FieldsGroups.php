<?php
/**
 * Catalog Brands Controler
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brands.php 68 2009-06-27 15:31:31Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class FieldsGroups extends PTA_WebModule
{
	private $_fieldGroup;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'FieldsGroups.tpl');

		$this->_fieldGroup = new PTA_Catalog_Field_Group('FieldGroup');
		$this->setModuleUrl(PTA_ADMIN_URL . '/FieldsGroups/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('FieldGroup');

		if (!empty($item)) {
			$this->_fieldGroup->loadById(intval($item));
		}

		switch (ucfirst($action)) {
			case 'Add': 
					$this->editAction();
			break;

			case 'List':
					$this->listAction();
			break;

			case 'Edit':
					$this->editAction();
			break;

			case 'EditFields':
					$this->editFieldsAction();
			break;

			case 'Delete':
				$this->deleteAction();
			break;

			case 'Copy':
				$this->editAction($item, true);
			break;

			default:
				$this->listAction();
		}
	}

	public function editAction($copy = false)
	{
		$this->setVar('tplMode', 'edit');
		$this->addVisual(new FieldsGroups_editForm('editForm', $this->_fieldGroup, $copy));
	}

	public function editFieldsAction()
	{
		$this->setVar('tplMode', 'editFields');
		$this->addVisual(new FieldsGroups_addFieldsForm('addFieldsForm', $this->_fieldGroup));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_fieldGroup->getTable();
		$categoryTable = PTA_DB_Table::get('Catalog_Category');
		
		$fields = $fieldTable->getFields();
		unset($fields['CATEGORYID']);
		
		$view = new PTA_Control_View('fieldsView', $this->_fieldGroup, array_values($fields));

		$view->join(
			array('cats' => $categoryTable->getTableName()),
			$fieldTable->getTableName() . '.' .$fieldTable->getFieldByAlias('categoryId')
			. ' = cats.' . $categoryTable->getPrimary(),
			array('CATEGORY_CATEGORY' => $categoryTable->getFieldByAlias('title'))
		);
		
		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Field Group', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/FieldGroup', 'edit.png');
		$view->addCommonAction('Add/Remove Fields', $this->getModuleUrl() . 'EditFields/FieldGroup', 'fields.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/FieldGroup', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/FieldGroup', 'remove.png');
	}

	public function deleteAction()
	{
		if ($this->_fieldGroup->getId()) {
			$this->_fieldGroup->remove();
		}

		$this->redirect($this->getModuleUrl());
	}

}
