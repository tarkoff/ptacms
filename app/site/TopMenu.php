<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: MainMenu.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class TopMenu extends PTA_WebModule
{
	private $_menu;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'TopMenu.tpl');
		$this->setModuleUrl(BASEURL . '/Catalog/List/Category/');
	}

	public function init()
	{
		parent::init();

		$categoryAlias = $this->getApp()->getHttpVar('Category', false);
		$categories = PTA_DB_Table::get('Catalog_Category')->getCategoriesByRootId(0);
/*
		foreach ($categories as $cid => $category) {
			$categories[$cid] = array_values($category);
		}
*/

		$this->getApp()->setCookie('Category', $categoryAlias, 0);
		$this->getApp()->setHttpVar('Category', $categoryAlias);

		$this->setVar('Categories', $categories);
		$this->setVar('selected', $this->quote($categoryAlias));
	}

}
