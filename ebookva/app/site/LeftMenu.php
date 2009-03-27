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
		$this->setModuleUrl(BASEURL . '/Category/');
	}

	public function init()
	{
		parent::init();
		
		$categoryAlias = $this->getApp()->getHttpVar('Category');
		if (empty($categoryAlias)) {
			$categoryAlias = $this->getApp()->getCookie('Category');
		}

		$this->setVar('Categories', PTA_Util::buildCategoryTree(PTA_DB_Table::get('Category')->getAll()));
		$this->setVar('selectedCategory', $this->quote($categoryAlias));
	}

}
