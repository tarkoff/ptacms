<?php
/**
 * Resources Controller
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: ResourcesController.php 309 2010-04-19 21:06:53Z TPavuk $
 */

class Default_ResourcesController extends KIT_Controller_Action_Backend_Abstract
{
	public function init()
	{
		$this->_helper->contextSwitch()
			->addActionContext('list', 'json')
			->addActionContext('add', 'json')
			->addActionContext('edit', 'json')
			->addActionContext('delete', 'json')
			->initContext();
	}

	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$resourceTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Resource');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxView($resourceTable));
		} else {
		}
	}
/*
	private function _getAjaxView()
	{
		$resourceTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Resource');

		$viewParams = array(
			'page'		=> $this->_getParam('page', 1),
			'rows'		=> $this->_getParam('rows', 20),
			'sortField' => $this->_getParam('sidx')
		);

		$filterParams = array(
			'searchField'  => $this->_getParam('searchField'),
			'searchString' => $this->_getParam('searchString'),
			'searchOper'   => $this->_getParam('searchOper')
		);

		$view = $resourceTable->getView($viewParams, $filterParams);
		$primaryField = $resourceTable->getPrimary();
		$jsonList = array();
		foreach ($view->rows as $row) {
			$jsonList[] = array(
				'id' => $row[$primaryField],
				'cell' => array_values($row)
			);
		}

		$view->rows = $jsonList;

		return $view;
	}
*/
	public function addAction()
	{
		$this->view->title = 'Resource Add Form';
		$this->_editForm();
	}

	public function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$id = (int)$this->_getParam('id', 0);
		if (empty($id) && !$isAjax) {
			$this->_redirect('resources/add');
		}

		$this->view->title = 'Resource Edit Form';
		$this->_editForm($id);
	}

	public function deleteAction()
	{
		$this->_delete(
			(int)$this->_getParam('id', 0),
			KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Resource')
		);
	}

}

