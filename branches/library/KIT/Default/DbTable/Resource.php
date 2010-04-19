<?php
/**
 * Resource Database Table
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
 * @version    $Id: Resource.php 278 2010-02-27 18:36:32Z TPavuk $
 */

class KIT_Default_DbTable_Resource extends KIT_Db_Table_Abstract
{
	protected $_name = 'RESOURCES';
	protected $_primary = 'RESOURCES_ID';

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
				$this->_name,
				array('RESOURCES_ID',
					  'RESOURCES_MODULE',
					  'RESOURCES_CONTROLLER',
					  'RESOURCES_ACTION')
			)
		);
	}

	public function getResourcesOptions()
	{
		static $resorcesOptions;

		if (!empty($resorcesOptions)) {
			return $resorcesOptions;
		}

		$resorcesOptions[0] = 'No Resource';
		foreach ($this->fetchAll() as $resource) {
			$resorcesOptions[$resource->RESOURCES_ID] =
			 "{$resource->RESOURCES_MODULE}/{$resource->RESOURCES_CONTROLLER}/{$resource->RESOURCES_ACTION}";
		}

		return $resorcesOptions;
	}
}
