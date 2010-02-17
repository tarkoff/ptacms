<?php
/**
 * Front Controller
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
 * @version    $Id$
 */

abstract class KIT_Controller_Action_Backend_Abstract extends Zend_Controller_Action
{
    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
    	parent::init();
    }
	
    /**
     * Pre-dispatch routines
     *
     * Called before action method. If using class with
     * {@link Zend_Controller_Front}, it may modify the
     * {@link $_request Request object} and reset its dispatched flag in order
     * to skip processing the current action.
     *
     * @return void
     */
    public function preDispatch()
    {
    	parent::preDispatch();
    }

    /**
     * Post-dispatch routines
     *
     * Called after action method execution. If using class with
     * {@link Zend_Controller_Front}, it may modify the
     * {@link $_request Request object} and reset its dispatched flag in order
     * to process an additional action.
     *
     * Common usages for postDispatch() include rendering content in a sitewide
     * template, link url correction, setting headers, etc.
     *
     * @return void
     */
    public function postDispatch()
    {
    	parent::postDispatch();
    }

    /**
     * Create edit form for entity
     *
     * @param int $id
     * @return KIT_Controller_Action_Backend_Abstract
     */
	protected function _editForm($id = 0)
	{
		$this->view->headTitle($this->view->title, 'APPEND');
		$isAjax = $this->getRequest()->isXmlHttpRequest();

		$module = $this->getRequest()->getModuleName();
		$controller = $this->getRequest()->getControllerName();
		$formName = ucfirst($module) . '_Form_' . ucfirst($controller) . '_Edit';

		$form = new $formName($id);
		$this->view->form = $form;

		if ($form->submit()) {
			if ($isAjax) {
				$this->_helper->json(1);
			} else {
				$this->_redirect("{$module}/{$controller}/list");
			}
		} else {
			if ($isAjax) {
				$this->_helper->json(0);
			}
		}
		return $this;
	}

	/**
	 * Build ajax view for entity
	 *
	 * @param KIT_Db_Table_Abstract $table
	 * @return stdClass
	 */
	protected function _getAjaxView(KIT_Db_Table_Abstract $table)
	{
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

		$view = $table->getView($viewParams, $filterParams);
		$primaryField = $table->getPrimary();
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

	/**
	 * Common method for deleting
	 *
	 * @param int $id
	 * @param KIT_Db_Table_Abstract $table
	 * @return boolean
	 */
	protected function _delete($id, KIT_Db_Table_Abstract $table)
	{
		if (empty($id) || empty($table)) {
			return false;
		}

		$deleted = false;
		if (!empty($id)) {
			$deleted = $table->removeById($id);
		}

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($deleted);
		}
		return $deleted;
	}
}