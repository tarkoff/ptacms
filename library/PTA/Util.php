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
	
	public function invoke($data, $method, $byField = false)
	{
		$data = (array)$data;
		$resultSet = array();
		if ($byField) {
			foreach ($data as $record) {
				$resultSet[] = $record[$method];
			}
		} else {
			foreach ($data as $record) {
				try {
					$resultSet[] = $record->$method();
				} catch (Exception $e) {}
			}
		}
		return $resultSet;
	}
	
	/**
	 * build option from array by setted fields
	 *
	 * @param array $data
	 * @param string $valueField
	 * @param string $labelField
	 * @return array
	 */
	public static function getOptionsFromArray($data, $valueField, $labelField)
	{
		if (!is_array($data)) {
			return array();
		}

		$resData = array();
		foreach ($data as $field) {
			$resData[] = array(@$field[$valueField], $field[$labelField]);
		}

		return $resData;
	}
	
	public static function upload($destPath, $uploader = null)
	{
		if (
			empty($uploader)
			|| !($uploader instanceof Zend_File_Transfer_Adapter_Abstract)
		) {
			$uploader = new Zend_File_Transfer_Adapter_Http();
		}

		if (empty($destPath)) {
			$uploader->setDestination(PTA_CONTENT_PHOTOS_PATH);
		} else {
			$uploader->setDestination($destPath);
		}

		if (!$uploader->isValid()) {
			return false;
		}

		if (!$uploader->receive()) {
			$messages = $uploader->getMessages();
			throw new PTA_Exception(implode("\n</br>", $messages));
		}

		if ($uploader->isReceived()) {
			$fileName = str_replace(PTA_ROOT_PATH, '', $uploader->getFileName());
			if (!empty($fileName)) {
				return DIRECTORY_SEPARATOR . ltrim($fileName, DIRECTORY_SEPARATOR);
			}
		}
		
		return false;
	}
}