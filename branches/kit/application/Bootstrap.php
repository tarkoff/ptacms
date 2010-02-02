<?php
/**
 * Application bootstrap
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
 * @version    $Id: Action.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAutoload()
	{
		$modulLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Default',
			'basePath' => APPLICATION_PATH.'/modules/default')
		);

		return $modulLoader;
	}
	
}

