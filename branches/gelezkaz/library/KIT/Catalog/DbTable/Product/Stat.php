<?php
/**
 * Catalog Product Statistic Database Table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

class KIT_Catalog_DbTable_Product_Stat extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_STAT';
	protected $_primary = 'SAT_PRODUCTID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$this->setViewSelect(
			$this->getAdapter()->select()->from(
				array('stats' => $this->_name),
				$this->getFields(false)
			)
		);
	}

	/**
	 * Update product statistic
	 *
	 * @param int $productId
	 * @return boolean
	 */
	public function updateStat($productId)
	{
		$productId = (int)$productId;
		if (empty($productId)) {
			return false;
		}
		
		return $this->getAdapter()->query(
			'INSERT INTO ' . $this->getTableName()
			. ' (SAT_PRODUCTID, SAT_VIEWS) VALUES (' . $productId . ', 1) '
			. 'ON DUPLICATE KEY UPDATE SAT_VIEWS=SAT_VIEWS+1'
		);
	}
	
	public function resetStat($productId = null)
	{
		
	}
}
