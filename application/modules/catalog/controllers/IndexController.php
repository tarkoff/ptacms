<?php
/**
 * Index Controller
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
 * @version    $Id: IndexController.php 273 2010-02-17 12:42:59Z TPavuk $
 */

class Catalog_IndexController extends KIT_Controller_Action_Backend_Abstract
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_forward('list', 'products', 'catalog');
    }

    public function listAction()
    {
        $this->_forward('list', 'products', 'catalog');
    }
}

