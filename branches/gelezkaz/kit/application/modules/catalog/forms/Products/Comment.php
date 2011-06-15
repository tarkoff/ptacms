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

class Catalog_Form_Products_Comment extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Catalog_Post
	 */
	private $_post;
	
	private $_protuctId;
	
	public function __construct($productId = 0, $options = null)
	{
		$productId = intval($productId);

		$this->_post = KIT_Model_Abstract::get('KIT_Catalog_Post');
		$this->_post->setProductId($productId);

		parent::__construct($options);
		$this->setName('commentForm');

		$title = new Zend_Form_Element_Text('author');
		$title->setLabel('Автор')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($title);

		$post = new Zend_Form_Element_Textarea('post');
		$post->setLabel('Комментарий')
			  ->setRequired(false)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($post);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Отправить');
		$this->addElement($submit);

	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			if ($this->isXmlHttpRequest()) {
				$newData = array();
				if (isset($formData['id']) && is_numeric($formData['id'])) {
					$newData['id'] = $formData['id'];
				} else {
					$newData['id'] = null;
				}
				foreach ($this->_post->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldAlias])) {
						$newData[$fieldAlias] = $formData[$fieldAlias];
					}
				}
				$formData = $newData;
			}

			if ($this->isValid($formData)) {
				$this->_post->setOptions($this->getValues());
				return $this->_post->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
