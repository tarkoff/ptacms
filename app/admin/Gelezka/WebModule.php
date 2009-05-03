<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Fields.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Gelezka_WebModule extends PTA_WebModule
{
	protected $_moduleItem;
	protected $_moduleUrlPrefix;
	protected $_httpItem;

	function __construct ($prefix, $urlPrefix, $httpItem)
	{
		parent::__construct($prefix, "{$urlPrefix}.tpl");
		$this->_moduleUrlPrefix = $urlPrefix;
		$this->_httpItem = $httpItem;

		$this->setModuleUrl(ADMINURL . "/{$urlPrefix}/");
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar($this->_httpItem);

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

	public function setModuleItem($item)
	{
		$this->_moduleItem = $item;
	}

	public function getModuleItem()
	{
		return $this->_moduleItem;
	}

	public function editAction($itemId = null, $copy = false)
	{
		$this->setVar('tplMode', 'edit');

		if (!empty($itemId)) {
			$this->_moduleItem->loadById($itemId);
		}

		$formName = "{$this->_moduleUrlPrefix}_editForm";
		$editForm = new $formName('editForm', $this->_moduleItem, $copy);
		$this->addVisual($editForm);
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_moduleItem->getTable();

		$fields = $fieldTable->getFields();
		
		$view = new PTA_Control_View("{$this->_moduleUrlPrefix}View", $this->_moduleItem, array_values($fields));

		$this->addActions($view);
		$res = $view->exec();

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . "Edit/{$this->_httpItem}", 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . "Copy/{$this->_httpItem}", 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . "Delete/{$this->_httpItem}", 'remove.png');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_moduleItem->loadById($itemId);
		}

		$this->_moduleItem->remove();

		$this->redirect($this->getModuleUrl());
	}

}
