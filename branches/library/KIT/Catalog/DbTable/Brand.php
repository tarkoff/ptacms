<?php
/**
 * Catalog Brand Database Table
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
 * @version    $Id: Brand.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class KIT_Catalog_DbTable_Brand extends KIT_Db_Table_Abstract
{
	protected $_name = 'CATALOG_BRANDS';
	protected $_primary = 'BRANDS_ID';

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
				array('brands' => $this->_name),
				$this->getFields(false)
			)
		);
	}

}