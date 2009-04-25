<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class TopList extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'TopList.tpl');
		$this->setModuleUrl(BASEURL . '/Book/view/Book/');
	}

	public function init()
	{
		parent::init();

//		$categoryAlias = $this->getApp()->getHttpVar('Category');
		$themeAlias = $this->getApp()->getHttpVar('Theme');
		
		$prodsTable = PTA_DB_Table::get('Catalog_Product');
		$statTable = PTA_DB_Table::get('Catalog_Product_Stat');
		$select = $prodsTable->select()->from(array('prods' => $prodsTable->getTableName()));
		$select->joinLeft(
					array('stat' => $statTable->getTableName()),
					'prods.' . $prodsTable->getPrimary() . ' = ' . $statTable->getFieldByAlias('productId'),
					array()
				);

		if (!empty($themeAlias)) {
			$catsTable = PTA_DB_Table::get('Catalog_Category');
			$select->join(
					array('cats' => $catsTable->getTableName()),
					'prods.'. $prodsTable->getPrimary() . ' = cats.' . $catsTable->getFieldByAlias('categoryId'),
					array()
				);
			$select->where('cats.' . $catsTable->getFieldByAlias('alias') . ' = ?', $themeAlias);
		}

		$select->order('stat.' . $statTable->getFieldByAlias('views') . ' desc')->limit(5);
//var_dump($select->getPart(Zend_Db_Table_Select::COLUMNS), $select->getPart(Zend_Db_Table_Select::FROM));
		$this->setVar('topList', $prodsTable->fetchAll($select)->toArray());
	}
}
