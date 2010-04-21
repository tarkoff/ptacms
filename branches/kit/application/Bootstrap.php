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
 * @version    $Id$
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
/*
	protected function _initPlugins()
	{
		//$aclPlugin = new Default_Plugin_Acl();
		//$front = $this->getResource('front');
		//$front->registerPlugin($aclPlugin);
	}
*/

	protected function _initLogger()
	{
		if ($this->getEnvironment() == 'development') {
			$logger = new Zend_Log(new Zend_Log_Writer_Firebug());
		} else {
			$logger = new Zend_Log(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data//logs/app.log'));
		}
		Zend_Registry::set('logger', $logger);
	}

	protected function _initViewHelpers()
	{
		$this->bootstrap('layout');
		$view = $this->getResource('layout')->getView();
		$view->addScriptPath(APPLICATION_PATH . '/layouts/scripts/generic/');
		
		$view->headTitle()->setSeparator(' :: ');
		$view->headTitle('SatDevice - Все для спутниквого телевидения');
		$view->keywords = array();
		$view->description = array('SatDevice - Все для спутниквого телевидения');
	}

}

