<?php
/**
 * Catalog Field Group Database Table
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
 * @version    $Id: Group.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_DbTable_Field_Group extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_FIELDGROUPS';
	protected $_primary = 'FIELDGROUPS_ID';

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
				array('fg' => $this->_name),
				$this->getFields(false)
			)
		);
	}

}
