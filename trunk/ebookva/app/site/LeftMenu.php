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

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'LeftMenu.tpl');
		$this->setModuleUrl(BASEURL . '/Catalog/List/Theme/');
	}

	public function init()
	{
		parent::init();

		$categoryAlias = $this->getApp()->getHttpVar('Category');
		$themeAlias = $this->getApp()->getHttpVar('Theme');

		$this->getApp()->setCookie('Theme', $themeAlias, 0);

		$this->setVar('Categories', PTA_DB_Table::get('Catalog_Category')->getCategoriesByRootAlias($categoryAlias));
		$this->setVar('selected', $this->quote($categoryAlias));
	}

}
