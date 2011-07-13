<?php
/**
 * User Authorization and Rights Plugin
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
 * @version    $Id: Menu.php 396 2010-05-04 20:46:03Z TPavuk $
 */

class Default_Plugin_Menu extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$view = Zend_Layout::getMvcInstance()->getView();
/*		$categoriesTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');

		$view->categories = $categoriesTable->getSelectedFields(
			array(
				$categoriesTable->getFieldByAlias('alias'),
				$categoriesTable->getFieldByAlias('title')
			),
			array($categoriesTable->getFieldByAlias('level') . ' = 0'),
			true
		);

		$view->activeCategory = $request->getControllerName();
		$activeCategory = $request->getParam('category');
		if (isset($view->categories[$activeCategory])) {
			$view->activeCategory = $activeCategory;
			$view->headTitle()->append($view->categories[$activeCategory]);
			$view->keywords[] = $view->categories[$activeCategory];
			$view->description[] = $view->categories[$activeCategory];
		}
*/
		$view->searchForm = new Catalog_Form_Categories_Search();
		if ($view->searchForm->submit()) {
			$request->setModuleName('catalog')
					->setControllerName('categories')
					->setActionName('search');

		}
	}
}
