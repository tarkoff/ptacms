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

class Books extends PTA_WebModule
{
	private $_catalog;
	
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Books.tpl');
		//$this->setModuleUrl(BASEURL . '/Product/list/Book');
	}

	public function init()
	{
		parent::init();

		$bookId = $this->getApp()->getHttpVar('Book');
		
		$this->setVar('book', current(PTA_DB_Table::get('Catalog_Product')->findById($bookId)));
	}
}
