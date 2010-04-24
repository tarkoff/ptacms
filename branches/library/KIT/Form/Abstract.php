<?php
/**
 * Abstract Form
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
abstract class KIT_Form_Abstract extends Zend_Form
{
	protected $_mode = self::MODE_ADD;

	const MODE_ADD = 0;
	const MODE_EDIT = 1;

	abstract public function submit();

	/**
	 * Detect post request
	 *
	 * @return mixed
	 */
	public function isPost()
	{
		return Zend_Controller_Front::getInstance()->getRequest()->isPost();
	}

	/**
	 * Get posted form data
	 *
	 * @return mixed
	 */
	public function getPost()
	{
		if (!$this->isPost()) {
			return array();
		}
		return Zend_Controller_Front::getInstance()->getRequest()->getPost();
	}

	/**
	 * Get request params
	 *
	 * @return mixed
	 */
	public function getParams()
	{
		return Zend_Controller_Front::getInstance()->getRequest()->getParams();
	}

	/**
	 * Detect ajax request
	 *
	 * @return boolean
	 */
	public function isXmlHttpRequest()
	{
		return Zend_Controller_Front::getInstance()->getRequest()->isXmlHttpRequest();
	}

	/**
	 * Set Form Elements Values From Model Object
	 *
	 * @param KIT_Model_Abstract $model
	 * @return KIT_Form_Abstract
	 */
	public function loadFromModel(KIT_Model_Abstract $model)
	{
		foreach ($this->getElements() as $element) {
			$method = 'get' . ucfirst($element->getName());
			if (method_exists($model, $method)) {
				$element->setValue($model->$method());
			}
		}
		return $this;
	}
}
