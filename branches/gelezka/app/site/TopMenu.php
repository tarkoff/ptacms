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

		$categories = PTA_DB_Table::get('Catalog_Category')->getCategoriesByRootId(0);

		$this->setCategory($this->getCategory());

		$this->setVar('Categories', $categories);
	}
	
	public function setCategory($alias)
	{
		$this->getApp()->setCookie('Category', $alias, 0);
		$this->setHttpVar('Category', $alias);
var_dump('category: ' . $alias);
		$this->setVar('selected', $alias);
	}
	
	public function getCategory()
	{
		return $this->getHttpVar('Category', false);
	}

}
