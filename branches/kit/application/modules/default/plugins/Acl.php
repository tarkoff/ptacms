<?php
/**
 * User Authorization and Rights Plugin
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

class Default_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$request->setModuleName('default')
					->setControllerName('auth')
					->setActionName('login');
		} else {
			$module = $request->getModuleName();
			$controller = $request->getControllerName();
			$action = $request->getActionName();
			//$front = Zend_Controller_Front::getInstance();

			$user = $auth->getIdentity();
			$resourceTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Resource');
			$menuTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Menu');
			$db = $resourceTable->getAdapter();

			$resource = new Default_Model_Resource();
			$resource->setOptions(
				current(
					$resourceTable->findByFields(
						array(
							'module'	 => $db->quoteInto('= ?', $module),
							'controller' => $db->quoteInto('= ?', $controller),
							'action'	 => $db->quoteInto('= ?', $action)
						)
					)
				),
				true
			);
/*
			$activeItems = array();
			if ($resource->getId()) {
				$activeMenuItems = $menuTable->findByFields(
					array('resourceId' => '=' . $resource->getId())
				);
				$menuIdField = $menuTable->getPrimary();
				foreach ($activeMenuItems as $menuItem) {
					$activeItems[$menuItem[$menuIdField]] = $menuItem[$menuIdField];
				}
			}
*/
			Zend_Registry::set('activeMenuItems', $activeItems);

			Zend_Registry::set('resource', $resource);
			Zend_Registry::set(
				'userMenu',
				$menuTable->getAllowedMenu($user->getId(), $user->getGroupId())
			);

			if (!$user->hasRight($resource)) {
				$request->setModuleName('default')
						->setControllerName('auth')
						->setActionName('login');
			}
		}

	}
}