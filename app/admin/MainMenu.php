<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class MainMenu extends PTA_WebModule
{
	private $_menu;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'MainMenu.tpl');
	}

	public function init()
	{
		parent::init();
/*		
		$action = $this->getApp()->getAction();
		$model = $this->getApp()->getModel();
		
		switch ($action) {
			case 'Add': 
					$this->addAction();
			break;
			
			case 'List':
					$this->listAction();
			break;
			
			case 'Edit':
					$this->editAction($model);
			break;
		}
*/
	}

	public function editAction($id=null)
	{
		$this->setVar('tplMode', 'edit');
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
	}

}
