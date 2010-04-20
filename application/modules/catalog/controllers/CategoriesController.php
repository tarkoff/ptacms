<?php
/**
 * Products Controller
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: CategoriesController.php 295 2010-04-19 12:19:24Z TPavuk $
 */

class Catalog_CategoriesController extends KIT_Controller_Action_Backend_Abstract
{
	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$catsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->json($this->_getAjaxTreeView($catsTable));
		} else {
			$this->view->cats = (array)$catsTable->getParentSelectOptions(
				$catsTable->getPrimary(),
				$catsTable->getFieldByAlias('title')
			);
		}
	}

}

