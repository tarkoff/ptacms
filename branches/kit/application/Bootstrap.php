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
	protected function _initViewHelpers()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();

		$view->doctype('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
		$view->headTitle()->setSeparator(' - ');
		$view->headTitle('KiT CMS Admin Panel');
	}
}

