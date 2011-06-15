<?php
/**
 * Catalog Category Database Table
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

class KIT_Catalog_DbTable_Category extends KIT_Db_Table_Tree_Abstract
{
	protected $_name = 'CATALOG_CATEGORIES';
	protected $_primary = 'CATEGORIES_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$select = $this->getAdapter()->select()->from(
			array('cat' => $this->_name),
			array(
				'CATEGORIES_ID',
				'CATEGORIES_TITLE',
				'CATEGORIES_ALIAS',
				'CATEGORIES_PARENTID',
				'CATEGORIES_LEVEL',
				'CATEGORIES_LEFT',
				'CATEGORIES_RIGHT'
			)
		);
/*
		$select->joinLeft(
			array('cat2' => $this->_name),
			'cat1.CATEGORIES_PARENTID = cat2.' . $this->_primary,
			array('CATEGORIES_PARENT' => 'IFNULL(cat2.CATEGORIES_TITLE,"None")')
		);
*/
		$this->setViewSelect($select);
	}

}
