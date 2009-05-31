<?php
/**
 * User Site Top Menu Controller
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class TopMenu extends PTA_WebModule
{
	private $_menu;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'TopMenu.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Catalog/List/Category/');
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
		$this->setVar('selected', $alias);
	}
	
	public function getCategory()
	{
		return $this->getHttpVar('Category', false);
	}

}
