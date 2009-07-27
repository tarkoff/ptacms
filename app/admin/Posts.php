<?php
/**
 * Catalog Brands Controler
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brands.php 95 2009-07-12 19:14:37Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Posts extends PTA_WebModule
{
	private $_post;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Posts.tpl');

		$this->_post = PTA_DB_Object::get('Catalog_Post');
		$this->setModuleUrl(PTA_ADMIN_URL . '/Posts/');
	}

	public function init()
	{
		parent::init();

		$action = $this->getApp()->getAction();
		$item = $this->getApp()->getHttpVar('Post');

		switch (ucfirst($action)) {
			case 'Add': 
					$this->editAction();
			break;

			case 'List':
					$this->listAction();
			break;

			case 'Edit':
					$this->editAction($item);
			break;

			case 'Delete':
				$this->deleteAction($item);
			break;

			case 'Copy':
				$this->editAction($item, true);
			break;

			default:
				$this->listAction();
		}
	}

	public function editAction($itemId = null, $copy = false)
	{
		$this->setVar('tplMode', 'edit');

		if (!empty($itemId)) {
			$this->_post->loadById($itemId);
		}

		$this->addVisual(new Posts_editForm('editForm', $this->_post, $copy));
	}

	public function listAction()
	{
		$this->setVar('tplMode', 'list');
		$fieldTable = $this->_post->getTable();

		$view = new PTA_Control_View('fieldsView', $this->_post, array_values($fieldTable->getFields()));

		$this->addActions($view);
		$this->setVar('view', $view->exec());
	}

	public function addActions(&$view)
	{
		//$view->addSingleAction('New Brand', $this->getModuleUrl() . 'Add/', 'add.png');

		$view->addCommonAction('Edit', $this->getModuleUrl() . 'Edit/Post', 'edit.png');
		$view->addCommonAction('Copy', $this->getModuleUrl() . 'Copy/Post', 'copy.png');
		$view->addCommonAction('Delete', $this->getModuleUrl() . 'Delete/Post', 'remove.png');
	}

	public function deleteAction($itemId)
	{
		if (!empty($itemId)) {
			$this->_post->loadById($itemId);
		}

		if (!$this->_post->remove()) {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while post delete!'
			);
		} else {
			$this->redirect($this->getModuleUrl());
		}
	}

}
