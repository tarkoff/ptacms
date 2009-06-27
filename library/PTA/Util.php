<?php
/**
 *  Util Module
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Util
{
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
			$uploader->setDestination(PTA_CONTENT_PATH);
		} else {
			self::createContentPath($destPath);
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
			$oldFileName = $uploader->getFileName();
			if (!empty($oldFileName)) {
				$fileProperties = pathinfo($oldFileName);
				$newFileName = $fileProperties['dirname']
					. '/' . self::getUniqueFileName($fileProperties['basename'])
					. '.' . $fileProperties['extension'];
				if (rename($oldFileName, $newFileName)) {
					return str_replace(PTA_CONTENT_PATH . '/', '', $newFileName);
				}
				return str_replace(PTA_CONTENT_PATH . '/', '', $oldFileName);
			}
		}
		
		return false;
	}
	
	/**
	 * Delete a file
	 *
	 * @param string $dest
	 * @return boolean
	 */
	public static function unlink($dest)
	{
var_dump($dest);
		// Sanity check
		if (!file_exists($dest)) {
			return false;
		}

		// Simple delete for a file
		if (is_file($dest) || is_link($dest)) {
			return unlink($dest);
		}

		return false;
	}
	
	/**
	 * Delete a folder and its contents
	 *
	 * @param string $dest
	 * @return boolean
	 */
	public static function rmDir($dest)
	{
		// Sanity check
		if (!is_dir($dest)) {
			return false;
		}

		// Loop through the folder
		$dir = dir($dest);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
 
			if (is_dir($entry)) {
				self::rmDir($entry);
			} else {
				self::unlink($dest . DIRECTORY_SEPARATOR . $entry);
			}
		}

		// Clean up
		$dir->close();
		return rmdir($dest);
	}
	
	public static function createContentPath($contentPath = null)
	{
		if (empty($contentPath)) {
			$contentPath = PTA_CONTENT_PHOTOS_PATH
				 . '/' . substr(md5(date('Ymd'), true), 0, 6);
		}

		clearstatcache();
		if (is_dir($contentPath)) {
			return false;
		}

		if (mkdir($contentPath, 0777)) {
			return $contentPath;
		}
		
		return false;
	}
	
	public static function getUniqueFileName($key = PTA_CONTENT_PHOTOS_PATH)
	{
		return  substr(md5($key), 0, 8);
	}
	
}