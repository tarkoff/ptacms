<?php
/**
 * Index Controller
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
 * @version    $Id: IndexController.php 330 2010-04-21 17:05:14Z TPavuk $
 */

class Default_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$categoriesTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');

		$categories = $categoriesTable->getSelectedFields(
			array(
				$categoriesTable->getPrimary(),
				$categoriesTable->getFieldByAlias('alias'),
				$categoriesTable->getFieldByAlias('title'),
				$categoriesTable->getFieldByAlias('parentId')
			)
		)->toArray();

		$this->view->categories = array();
		foreach ($categories as $catIndx => $category) {
			if (empty($category['CATEGORIES_PARENTID'])) {
				$this->view->categories[$category['CATEGORIES_ID']] = $category;
				unset($categories[$catIndx]);
			}
		}

		foreach ($categories as $category) {
			if (!empty($category['CATEGORIES_PARENTID'])
				&& isset($this->view->categories[$category['CATEGORIES_PARENTID']])
			) {
				$this->view->categories[$category['CATEGORIES_PARENTID']]['childs'][] = $category;
			}
		}

		//$this->view->popular = $prodsTable->getPopular(6);
		$this->view->newest  = $prodsTable->getNewest(10);
		//var_dump($this->view->newest);
	}
}

