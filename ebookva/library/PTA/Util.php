<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Categories.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Util
{
	/**
	 * Build categories tree by categories array
	 *
	 * @param array $categories
	 * @return array
	 */
	public static function buildCategoryTree($categories)
	{
		$catTable = PTA_DB_Table::get('Category');
		$catIdField = $catTable->getPrimary();
		$catParentIdField = $catTable->getFieldByAlias('parentId');
		$catTitleField = $catTable->getFieldByAlias('title');
		$catAliasField = $catTable->getFieldByAlias('alias');

		$resList = array();
		foreach ($categories as $category) {
			if (empty($category[$catParentIdField])) {
				$resList[$category[$catIdField]]['title'] = $category[$catTitleField];
				$resList[$category[$catIdField]]['alias'] = $category[$catAliasField];
				$resList[$category[$catIdField]]['childs'] = array();
			} else {
				foreach ($resList as $rootCatId => $catChilds) {
					if (
						in_array($category[$catParentIdField], array_keys($catChilds['childs']))
						|| ($rootCatId == $category[$catParentIdField])
					) {
						$resList[$rootCatId]['childs'][$category[$catIdField]]['title'] = $category[$catTitleField];
						$resList[$rootCatId]['childs'][$category[$catIdField]]['alias'] = $category[$catAliasField];
					}
				}
			}
		}
		return $resList;
	}
}