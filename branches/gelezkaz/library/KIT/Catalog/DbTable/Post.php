<?php
/**
 * Catalog Post Database Table
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
 * @version    $Id: Field.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_DbTable_Post extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_POSTS';
	protected $_primary = 'POST_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$productsTable = self::get('KIT_Catalog_DbTable_Product');
		$brandsTable   = self::get('KIT_Catalog_DbTable_Brand');
		
		$select = $this->getAdapter()->select();

		$select->from(
			array('posts' => $this->_name),
			array(
				$this->getPrimary(),
				'POST_PRODUCTID' => "CONCAT_WS(' ', brands." 
										  . $brandsTable->getFieldByAlias('title') . ', '
										  . $productsTable->getFieldByAlias('title') . ')',
				$this->getFieldByAlias('postDate'),
				'POST_POST' => 'LEFT(' . $this->getFieldByAlias('post') . ', 40)',
				$this->getFieldByAlias('author')
			)
		);

		$select->join(
			array('prods' => $productsTable->getTableName()),
			'prods.PRODUCTS_ID = posts.' . $this->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('brands' => $brandsTable->getTableName()),
			'prods.PRODUCTS_BRANDID = brands.' . $brandsTable->getPrimary(),
			array()
		);

		$this->setViewSelect($select);
	}

}
