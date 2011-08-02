<?php
/**
 * Index Controller
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: IndexController.php 330 2010-04-21 17:05:14Z TPavuk $
 */

class Default_SitemapController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->contextSwitch
			 ->addActionContext('index', 'xml')
			 ->initContext();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$categoriesTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Category');
		$prodsTable      = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$brandsTable     = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Brand');

		$categoryAliasField = $categoriesTable->getFieldByAlias('alias');
		$categoryTitleField = $categoriesTable->getFieldByAlias('title');

		$productAliasField = $prodsTable->getFieldByAlias('alias');
		$productTitleField = $prodsTable->getFieldByAlias('title');
		$productDateField  = $prodsTable->getFieldByAlias('date');

		$brandTitleField = $brandsTable->getFieldByAlias('title');

		$categories = $categoriesTable->getSelectedFields(
			array($categoryAliasField, $categoryTitleField)
		)->toArray();


		$navigation = new Zend_Navigation();


		foreach ($categories as $catIndx => $category) {
			$navigation->addPage(Zend_Navigation_Page::factory(array(
					'label'      => $category[$categoryTitleField],
					'module'     => 'catalog',
					'controller' => 'categories',
					'action'     => 'list',
					'params'     => array('category' => $category[$categoryAliasField])
			))->set('changefreq', 'weekly')
			  ->set('priority', '1.0'));
		}

		$prodsSelect = $prodsTable->getCatalogSelect();
		$prodsSelect->order('prods.' . $productDateField . ' DESC');

		$priority = 0.9;
		$index = 1;
		foreach ($prodsTable->fetchAll($prodsSelect) as $product) {
			$navigation->addPage(Zend_Navigation_Page::factory(array(
					'label'      => $product->$brandTitleField . ' ' . $product->$productTitleField,
					'module'     => 'catalog',
					'controller' => 'products',
					'action'     => 'view',
					'params'     => array('product' => $product->$productAliasField)
			))->set('changefreq', 'monthly')
			  ->set('priority', $priority)
			  ->set('lastmod', $product->$productDateField));

			if (($index % 500) == 0 && $priority > 0.5) {
				$priority -= 0.1;
			}
			$index++;

		}

		Zend_Registry::set('Zend_Navigation', $navigation);
	}

	public function redirectAction()
	{
		$this->_redirect('sitemap');
	}
}

