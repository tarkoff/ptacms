<?php
/**
 * Users Database Table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

class Catalog_Model_DbTable_Product extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_PRODUCTS';
	protected $_primary = 'PRODUCTS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
		$usersTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_User');
		$select = $this->getAdapter()->select();

		$select->from(array('prods' => $this->_name),
					  array('PRODUCTS_ID',
							'PRODUCTS_ALIAS',
							'PRODUCTS_TITLE',
							'PRODUCTS_DATE'));

		$select->join(array('usr' => $usersTable->getTableName()),
					  'prods.PRODUCTS_AUTHORID = usr.' . $usersTable->getPrimary(),
					  array('PRODUCTS_AUTHORID' => $usersTable->getFieldByAlias('login')));
//var_dump($select->assemble());
//exit(0);
		$this->setViewSelect($select);
	}
}
