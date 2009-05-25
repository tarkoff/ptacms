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

class LeftMenu extends PTA_WebModule
{
	private $_menu;
	private $_theme;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'LeftMenu.tpl');
	}

	public function init()
	{
		parent::init();

		//$this->setTheme($this->getTheme());
	}
	
	public function shutdown()
	{
		parent::init();

		$categoryAlias = $this->getModule('TopMenu')->getCategory();
		$themeAlias = $this->getApp()->getHttpVar('Theme');

//		$categoryAlias = $this->getApp()->getCookie('Category');

		if (($cookieCategoryAlias = $this->getApp()->getCookie('Category'))) {
			if ($cookieCategoryAlias != $categoryAlias) {
				$themeAlias = '';
			}
		}

		$this->setTheme($themeAlias);

		if (empty($categoryAlias)) {
			$this->setVar('Themes', PTA_DB_Table::get('Catalog_Category')->getCategoriesByRootId(0));
			$this->setModuleUrl(BASEURL . '/Catalog/List/Category/');
		} else {
			$this->setVar('Themes', PTA_DB_Table::get('Catalog_Category')->getCategoriesByRootAlias($categoryAlias));
			$this->setModuleUrl(BASEURL . "/Catalog/List/Category/{$categoryAlias}/Theme/");
		}
	}

	
	public function setTheme($alias)
	{
		$this->getApp()->setCookie('Theme', $alias, 0);
		$this->getApp()->setHttpVar('Theme', $alias);

		$this->setVar('selected', $alias);
	}
	
	public function getTheme()
	{
		return $this->getApp()->getHttpVar('Theme', false);
	}
}
