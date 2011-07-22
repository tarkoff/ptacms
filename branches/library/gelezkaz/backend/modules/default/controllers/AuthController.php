<?php
/**
 * Authorization Controller
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
 * @version    $Id: AuthController.php 273 2010-02-17 12:42:59Z TPavuk $
 */

class Default_AuthController extends KIT_Controller_Action_Backend_Abstract
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$this->_forward('login');
	}
	
	public function loginAction()
	{
		$loginForm = new Default_Form_Auth_Login();
		$this->view->form = $loginForm;

		if ($loginForm->submit()) {
			$this->_redirect('/');
		}
	}

	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect('/');
	}

}

