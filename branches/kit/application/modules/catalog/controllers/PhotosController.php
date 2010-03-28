<?php
/**
 * Catalog Fields Controller
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
 * @version    $Id$
 */

class Catalog_PhotosController extends KIT_Controller_Action_Backend_Abstract
{
	public function init()
	{
		$this->_helper->contextSwitch()
			 ->addActionContext('list', 'json')
			 ->addActionContext('add', 'json')
			 ->addActionContext('edit', 'json')
			 ->addActionContext('delete', 'json')
			 ->initContext();
	}

	public function indexAction()
	{
		$this->_forward('list');
	}

	public function listAction()
	{
		$productId = (int)$this->_getParam('pid', 0);

		$this->view->pid = $productId;
		$this->view->photos     = array();
		$this->view->form = new Catalog_Form_Photos_Upload($productId);

		if (!empty($productId)) {
			$photosTable = KIT_Db_Table_Abstract::get('Catalog_Model_DbTable_Product_Photo');
			$this->view->form->submit();
			$this->view->photos = $photosTable->getProductPhotos($productId);
		}
	}

	public function addAction()
	{
		$this->_editForm();
	}

	public function editAction()
	{
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		if ($isAjax && ('del' == $this->_getParam('oper', 'edit'))) {
			$this->deleteAction();
		}

		$id = (int)$this->_getParam('id', 0);
		if (empty($id) && !$isAjax) {
			$this->_redirect('catalog/photos/add');
		}

		$this->_editForm($id);
	}

	public function deleteAction()
	{
		$id = (int)$this->_getParam('id', 0);
		$photo = KIT_Model_Abstract::get('Catalog_Model_Product_Photo', $id);
		if ($photo->getId()) {
			$fileName = realpath(APPLICATION_PATH . '/../public') . $photo->getFile();
			if (unlink($fileName)) {
				$this->_delete((int)$this->_getParam('id', 0), $photo->getDbTable());
			} else {
				throw new Zend_Exception('Cannot delete photo:' . $fileName);
			}
		} else {
			$this->_redirect('catalog/products/');
		}
		$this->_redirect('catalog/photos/list/pid/' . $photo->getProductId());
	}
	
	public function defaultphotoAction()
	{
		$id = (int)$this->_getParam('id', 0);
		
		$photo = KIT_Model_Abstract::get('Catalog_Model_Product_Photo', $id);
		if ($photo->getId()) {
			$photo->setIsDefault(true);
			$photo->save();
		}
		$this->_redirect('catalog/photos/list/pid/' . $photo->getProductId());
	}
}

