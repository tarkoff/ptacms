<?php
/**
 * Catalog Posts Edit Form
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
 * @version    $Id: Edit.php 304 2010-04-19 19:07:18Z TPavuk $
 */

class Catalog_Form_Posts_Edit extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Post
	 */
	private $_post;
	
	/**
	 * @var KIT_Catalog_Product
	 */
	private $_protuct;
	
	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);

		$this->_post = KIT_Model_Abstract::get('KIT_Catalog_Post', $id);

		parent::__construct($options);
		$this->setName('editForm');

		$post = new Zend_Form_Element_Textarea('post');
		$post->setLabel('Post')
			  ->setRequired(false)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($post);

		$productsTable = KIT_Db_Table_Abstract::get('KIT_Catalog_DbTable_Product');
		$productId = new Zend_Form_Element_Select('productid');
		$productId->setLabel('Product')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim');
		$productId->addMultiOptions(
			$productsTable->getSelectedFields(
				array($productsTable->getPrimary(), $productsTable->getFieldByAlias('alias')),
				null,
				true
			)
		);
		$this->addElement($productId);

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);

		if (!empty($id)) {
			$this->loadFromModel($this->_post);
			$submit->setLabel('Save');
			$this->setLegend(' Post Edit Form');
		} else {
			$submit->setLabel('Add');
			$this->setLegend('Post Add Form');
		}
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			if ($this->isXmlHttpRequest()) {
				$newData = array();
				if (is_numeric($formData['id'])) {
					$newData['id'] = $formData['id'];
				} else {
					$newData['id'] = null;
				}
				foreach ($this->_post->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$newData[$fieldAlias] = $formData[$fieldName];
					}
				}
				$formData = $newData;
			}

			if ($this->isValid($formData)) {
				$this->_post->setOptions($this->getValues());
				
				$auth = Zend_Auth::getInstance();
				if (!$this->_post->getAuthor()) {
					if ($auth->hasIdentity()) {
						$user = $auth->getIdentity();
						$this->_post->setAuthor($user->getLogin());
					} else {
						$this->_post->setAuthor('Guest');
					}
				}
				return $this->_post->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
