<?php
set_include_path(
	rtrim(realpath(dirname(dirname(dirname(dirname(__FILE__))))), '/') . '/library'
	. PATH_SEPARATOR
	. get_include_path()
);

require_once 'Zend/Db.php';
require_once 'Zend/Navigation.php';
require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
//var_dump(get_include_path());


class Sitemap
{
	protected $_db;
	protected $_container;
	protected $_sitemap;
	protected $_frontController;
	protected $_router;
	
	public function __construct()
	{
		$this->_frontController = Zend_Controller_Front::getInstance();
		$this->_router = $this->_frontController->getRouter();

		$this->_db = Zend_Db::factory('Pdo_Mysql', array(
    		'host'     => '127.0.0.1',
    		'username' => 'root',
    		'password' => '',
    		'dbname'   => 'satdevic_satdevice'
		));
		
		$this->_container = new Zend_Navigation(array(Zend_Navigation_Page::factory(array(
			'controler' => 'index',
			'action'    => 'index'
		))));

	}
	
	public function build()
	{
		foreach ($db->fetchAll('select PRODUCTS_ALIAS, PRODUCTS_TITLE from CATALOG_PRODUCTS limit 50') as $item) {
			$container->addPage(Zend_Navigation_Page::factory(array(
				'label'		=> $item['PRODUCTS_TITLE'],
				'module'    => 'catalog',
				'controler' => 'products',
				'action'    => 'view',
				'params'	=> array('product' => $item['PRODUCTS_ALIAS'])
			)));
		}

		$sitemap = new Zend_View_Helper_Navigation_Sitemap();
		$sitemap->setContainer($container);

		var_dump($sitemap->getDomSitemap());
		
	}
}

$siteMap = new Sitemap();
$siteMap->build();
