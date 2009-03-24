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
		$categories = $catTable->getAll();
//var_dump($categories);
		$catIdField = $catTable->getPrimary();
		$catParentIdField = $catTable->getFieldByAlias('parentId');
		$catTitleField = $catTable->getFieldByAlias('title');

		$resList = array();
		foreach ($categories as $category) {
			if (empty($category[$catParentIdField])) {
				$resList[$category[$catIdField]]['title'] = $category[$catTitleField];
				$resList[$category[$catIdField]]['childs'] = array();
			} else {
				foreach ($resList as $rootCatId => $catChilds) {
					if (
						in_array($category[$catParentIdField], array_keys($catChilds['childs']))
						|| ($rootCatId == $category[$catParentIdField])
					) {
						$resList[$rootCatId]['childs'][$category[$catIdField]] = $category[$catTitleField];
					}
				}
			}
		}
//var_dump($resList);
		$this->setVar('categories', $resList);
	}

	public function addActions(&$view)
	{
//		$view->addSingleAction('New', $this->getModuleUrl() . 'Add/', 'add.png');
//		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Category', 'edit.png');
	}
}
