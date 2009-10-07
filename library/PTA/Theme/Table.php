<?php
/**
 * Site Theme Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 111 2009-07-20 13:27:18Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Theme_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'THEMES';
	protected $_primary = 'THEME_ID';

	public function getSiteThemes($siteId)
	{
		if (empty($siteId)) {
			return array();
		}

		return $this->fetchAll(
			$this->select()->where(
				$this->getFieldByAlias('siteId') . ' = ' . intval($siteId)
			)
		)->toArray();
	}
	
	public function getActiveTheme($siteId)
	{
		if (empty($siteId)) {
			return array();
		}
		
		$select = $this->select();
		$select->where($this->getFieldByAlias('siteId') . ' = ' . intval($siteId));
		$select->where($this->getFieldByAlias('active') . ' = 1')->limit(1);

		return $this->fetchAll($select)->toArray();
	}
}
