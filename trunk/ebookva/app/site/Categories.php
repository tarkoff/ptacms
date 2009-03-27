<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Categories.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Categories extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Categories.tpl');
		$this->setModuleUrl(BASEURL . '/Categories/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$this->setVar('tplMode', 'list');

		switch (ucfirst($action)) {
			case 'List':
					$this->mainPageAction();
			break;

			default:
				$this->mainPageAction();
		}
	}

	public function mainPageAction()
	{
		$catTable = PTA_DB_Table::get('Category');
		$this->setVar('categories', PTA_Util::buildCategoryTree($catTable->getAll()));
	}

	public function addActions(&$view)
	{
//		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');
//		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Category', 'edit.png');
	}
}
