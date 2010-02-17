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

			$user = new Default_Model_User();
			$user->setOptions($auth->getIdentity(), true);
			if (Default_Model_User::ADMINISTRATOR_ID != $user->getId()) {
				$resourceTable = KIT_Db_Table_Abstract::get('Default_Model_DbTable_Resource');
				$db = $resourceTable->getAdapter();
				$resourceData = $resourceTable->findByFields(
					array(
						'module'	 => $db->quoteInto('= ?', $module),
						'controller' => $db->quoteInto('= ?', $controller),
						'action'	 => $db->quoteInto('= ?', $action)
					)
				);

				$resource = new Default_Model_Resource();
				$resource->setOptions(current($resourceData), true);
				if (!$user->hasRight($resource)) {
					//var_dump(array($module, $controller, $action));
					$request->setModuleName('default')
							->setControllerName('auth')
							->setActionName('login');
				}
			}
		}

	}
}