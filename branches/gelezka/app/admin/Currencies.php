<?php
/**
 * Currencies Controller
 *
 * @package Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Users.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Currencies extends PTA_WebModule
{
	private $_currency;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Currencies.tpl');

		$this->_currency = PTA_DB_Object::get('Catalog_Currency');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Currencies/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Currencies');

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

	public function editAction($itemId = null, $copy = false)
	{
		$this->setVar('tplMode', 'edit');

		if (!empty($itemId)) {
			$this->_currency->loadById($itemId);
		}

		$this->addVisual(new Currencies_editForm('editForm', $this->_currency, $copy));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_currency->getTable();

		$fields = $fieldTable->getFields();
		
		$view = new PTA_Control_View('currenciesView', $this->_currency, array_values($fields));

		$this->addActions($view);
		$res = $view->exec();

		$this->setVar('view', $res);
	}

	public function addActions(&$view)
	{
		$view->addSingleAction('New Currency', $this->getModuleUrl() . 'Add/', 'Add');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Currencies', 'Edit');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Currencies', 'Copy');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Currencies', 'Remove');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_currency->loadById($itemId);
		}

		$this->_currency->remove();

		$this->redirect($this->getModuleUrl());
	}

}
