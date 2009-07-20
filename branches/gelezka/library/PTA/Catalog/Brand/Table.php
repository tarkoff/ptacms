<?php
/**
 * Catalog Brand Table
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Brand_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'CATALOG_BRANDS';
	protected $_primary = 'BRANDS_ID';

	public function getByAlias($alias)
	{
		$alias = trim($alias);
		if (empty($alias)) {
			return array();
		}
		
		$select = $this->select()->where(
			$this->getFieldByAlias('alias') 
			. ' like "' . addslashes(htmlspecialchars(strip_tags($alias))) . '%"'
		);
		
		return $this->fetchAll($select)->toArray();
	}
}
