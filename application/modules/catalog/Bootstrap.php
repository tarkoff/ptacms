<?php
/**
 * Catalog Package Bootstrap
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

class Catalog_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initAutoload()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace(
			array(
				'namespace' => 'Catalog',
				'basePath' => APPLICATION_PATH.'/modules/catalog'
			)
		);

		return $autoloader;
	}

}