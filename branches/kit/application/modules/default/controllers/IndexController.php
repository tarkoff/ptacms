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
 * @version    $Id$
 */

class Default_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$prodsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');

		$this->view->popular = $prodsTable->getPopular(6);
		$this->view->newest  = $prodsTable->getNewest(6);
	}
}

