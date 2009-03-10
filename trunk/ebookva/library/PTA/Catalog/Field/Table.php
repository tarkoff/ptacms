<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'PRODUCTSFIELDS';
	protected $_primary = 'PRODUCTSFIELDS_ID';
	protected $_sequence = true;
	
	
	public function getFieldsByCategory($categoryId)
	{
		$select = $this->select()->where(
										$this->getFieldByAlias('category') . '=?',
										(int)$categoryId
									);
		return $this->fetchAll($select)->toArray();
	}
}
