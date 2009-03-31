<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Catalog.php 25 2009-03-16 21:32:59Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog extends PTA_WebModule
{
	private $_catalog;
	
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->_catalog = new PTA_Catalog_Product('Catalog');

		$this->setModuleUrl(BASEURL . '/Catalog/list/');
	}

	public function init()
	{
		parent::init();

		//$categoryAlias = $this->getApp()->getHttpVar('Category');
		$themeAlias = $this->getApp()->getHttpVar('Theme');

//var_dump($categoryAlias, $themeAlias);
		if (empty($themeAlias)) {
			$this->mainPage();
		}
		
	}
	
	public function mainPage()
	{
		$this->setVar('tplMode', 'mainPage');

		//$this->getApp()->insertModule('NewList', 'NewList');
		$this->getApp()->insertModule('TopList', 'TopList');
		$this->getApp()->getModule('TopList')->init();
		
	}

}
