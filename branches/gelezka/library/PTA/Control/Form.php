<?php

abstract class PTA_Control_Form extends PTA_Object 
{
	protected $_elements = array();
	protected $_data = array();
	protected $_submitted = false;

	function __construct ($prefix, $title = '')
	{
		$this->setPrefix($prefix);

		$this->setName($prefix);
		$this->setMethod('post');
		$this->setEnctype('application/x-www-form-urlencoded');
		//$this->setEnctype('multipart/form-data');
		$this->setTitle($title);
		$this->setAction('?');

		if ($this->getHttpVar($prefix . '_' . $prefix) == md5($prefix)) {
			$this->_submitted = true;
		}
	}

	public function init()
	{
		parent::init();

		$this->addVisual(
					new PTA_Control_Form_Hidden($this->getPrefix(), 
					'', 
					true, 
					md5($this->getPrefix()))
				);

		if ($this->submitted()) {
			$data = $this->_fillToData();
			$this->onSubmit($data);
			$this->initForm();
			$this->_fillFromData($data);
		} else {
			$this->initForm();
			$data = $this->onLoad();
			$this->_fillFromData($data);
		}

		$this->_initVisualElements();
		//$this->_submitted = $this->_submitted();
	}

	public function run()
	{
		parent::run();

		foreach ($this->getVisualAll() as $field) {
			$field->run();
		}
	}

	private function _initVisualElements()
	{
		$elements = $this->getVisualAll();
		if (!empty($elements)) {
			foreach ($elements as $element) {
				$element->init();
			}
		}
	}

	protected function _fillFromData($data)
	{
		$data = (array)$data;
		foreach ($data as $name => $value) {
			if (($field = $this->getVisual($name))) {
				$field->setValue($value);
			}
		}
	}

	protected function _fillToData(&$data = null)
	{
		if (empty($data)) {
			$data = new stdClass();
		}

		if ($this->submitted()) {
			$formPrefix = $this->getPrefix();
			$formPrefixLen = strlen($formPrefix);
			foreach ($_REQUEST as $httpVarAlias => $httpVarValue) {
				if (0 === strpos($httpVarAlias, $formPrefix)) {
					$httpVarAlias = substr($httpVarAlias, $formPrefixLen + 1, strlen($httpVarAlias));
					$data->$httpVarAlias = $httpVarValue;
				}
			}
		} else {
			foreach ($this->getVisualAll() as $field) {
				$data->{$field->getName()} = $field->getValue();
			}
		}
		return $data;
	}

	public function initForm() {}

	public function onLoad() {}

	public function submitted()
	{
		return $this->_submitted;
	}

	private function _submitted()
	{
		$submit = $this->getVisualByType(PTA_Control_Form_Field::TYPE_SUBMIT , true);
		$submitImage = $this->getVisualByType(PTA_Control_Form_Field::TYPE_IMAGE, true);
		$submitElement = (empty($submit) ? $submitImage : $submit);
		if (!empty($submitElement)) {
			$httpSubmit = $this->getHttpVar($this->getPrefix() . '_' . $submitElement->getName());
			if (!empty($httpSubmit)) {
				return true;
			}
		}
		return false;
	}

	public function onSubmit(&$data) {
		return true;
	}

	/**
	 * prepeare form for template
	 *
	 * @return stdClass
	 */
	public function toString()
	{
		$object = parent::toString();

		$elements = $this->getVisualAll();
		$data = array();
		foreach ($elements as $element) {
			$element->setName($this->getPrefix() . '_' . $element->getName());
			$data[$element->getPrefix()] = $element->toString();
		}
		
		uasort($data, array($this, "sortData"));
		$object->data = $data;
		return $object;
	}

	public static function sortData($a, $b)
	{
		$orderA = (isset($a->sortOrder) ? $a->sortOrder : 0); 
		$orderB = (isset($b->sortOrder) ? $b->sortOrder : 0);
		
		if ( $orderA == $orderB) {
			return 0;
		}
		return ($orderA > $orderB) ? +1 : -1;
	}

	public function addVisual($element)
	{
		$element->setFormPrefix($this->getPrefix());
		$this->_elements[$element->getName()] = $element;
	}

	public function getMethod()
	{
		return $this->getVar('method');
	}

	public function setMethod($value)
	{
		$this->setVar('method', $value);
	}

	public function getEnctype()
	{
		return $this->getVar('enctype');
	}

	public function setEnctype($value)
	{
		$this->setVar('enctype', $value);
	}

	public function getCssClass()
	{
		return $this->getVar('cssClass');
	}

	public function setCssClass($value)
	{
		$this->setVar('cssClass', $value);
	}

	public function getTitle()
	{
		return $this->getVar('title');
	}

	public function setTitle($value)
	{
		$this->setVar('title', $value);
	}

	public function getName()
	{
		return $this->getVar('name');
	}

	public function setName($value)
	{
		$this->setVar('name', $value);
	}

	public function getAction()
	{
		return $this->getVar('action');
	}

	public function setAction($value)
	{
		$this->setVar('action', $value);
	}

	public function getVisualAll()
	{
		return $this->_elements;
	}

	public function getVisual($prefix)
	{
		return (isset($this->_elements[$prefix]) ? $this->_elements[$prefix] : null);
	}

	public function getVisualByType($type, $single = false)
	{
		$elements = $this->getVisualAll();

		$res = array();
		if (!empty($elements)) {
			foreach ($elements as $element) {
				if ($element->getFieldType() == $type) {
					if ($single) {
						return $element;
					}
					
					$res[] = $element;
				}
			}
		}

		return $res;
	}

	public function validate($data)
	{
		$elements = $this->getVisualAll();
		$notValidElements = array();

		foreach ($elements as $element) {
			$name = $element->getName();
			if ($element->isMandatory() && empty($data->$name)) {
				$notValidElements[] = $element;
			}
		}

		return $notValidElements;
	}

}
