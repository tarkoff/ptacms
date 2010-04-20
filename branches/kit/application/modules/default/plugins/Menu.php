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
 * @version    $Id$
 */

class Default_Plugin_Menu extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$categoriesTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');

		$layout = Zend_Layout::getMvcInstance();
		$layout->categories = $categoriesTable->getSelectedFields(
			array(
				$categoriesTable->getFieldByAlias('alias'),
				$categoriesTable->getFieldByAlias('title')
			),
			array($categoriesTable->getFieldByAlias('level') . ' = 0'),
			true
		);
		
		$layout->activeCategory = '';
		$activeCategory = $request->getParam('category');
		if (isset($layout->categories[$activeCategory])) {
			$layout->activeCategory = $activeCategory;
		}
		Zend_Registry::get('logger')->err($layout->activeCategory);
 

	}
}
