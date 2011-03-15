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
			$resourceTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Resource');
			$menuTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_Menu');
			$db = $resourceTable->getAdapter();

			$resource = new KIT_Default_Resource();
			$resource->setOptions(
				current(
					$resourceTable->findByFields(
						array(
							'module'	 => $module,
							'controller' => $controller,
							'action'	 => $action
						)
					)
				),
				true
			);

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