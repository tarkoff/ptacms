<?php
/**
 * Catalog Field Database Table
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
 * @version    $Id: Menu.php 278 2010-02-27 18:36:32Z TPavuk $
 */

class Catalog_Model_DbTable_Field extends KIT_Db_Table_Tree_Abstract
{
	protected $_name = 'CATALOG_FIELDS';
	protected $_primary = 'FIELDS_ID';

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
				array('fields' => $this->_name),
				$this->getFields(false)
			)
		);
	}

}
