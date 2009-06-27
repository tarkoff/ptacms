<?php
/**
 * User Site Left Navigation Menu Controller
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
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

		$categoryAlias = $this->getModule('TopMenu')->getHttpCategory();
		$themeAlias = $this->getApp()->getHttpVar('Theme');

//		$categoryAlias = $this->getApp()->getCookie('Category');

		if (($cookieCategoryAlias = $this->getApp()->getCookie('Category'))) {
			if ($cookieCategoryAlias != $categoryAlias) {
				$themeAlias = '';
			}
		}

		$this->setTheme($themeAlias);

		$categoryId = 0;
		if (empty($categoryAlias)) {
			//$this->setVar('Themes', PTA_DB_Table::get('Catalog_Category')->getChildsById(0));
			$this->setModuleUrl(PTA_BASE_URL . '/Catalog/List/Category/');
		} else {
			//$this->setVar('Themes', PTA_DB_Table::get('Catalog_Category')->getChildsByAlias($categoryAlias));
			$this->setModuleUrl(PTA_BASE_URL . "/Catalog/List/Category/{$categoryAlias}/Theme/");
			$categoryTable = PTA_DB_Table::get('Catalog_Category');
			$category = $categoryTable->findByFields(
				array('alias'), array($categoryAlias)
			);
			$categoryId = (int)$category[0][$categoryTable->getPrimary()];
			
		}

		$categories = PTA_Catalog_Category::getCategoriesTree()->getBrancheFrom($categoryId);
//var_dump($categories);
		$this->setVar('Themes', $categories);
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
