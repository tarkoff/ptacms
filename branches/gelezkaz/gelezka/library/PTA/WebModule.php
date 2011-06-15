<?php
/**
 *  PTA App Web Module
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_WebModule extends PTA_Module
{
	private $_template = null;
	private $_visuals = array();

	public function __construct($prefix, $tpl=null)
	{
		parent::__construct($prefix);
		
		if(!empty($tpl)){
			$this->setTemplate($tpl);
		}
	}

	public function init()
	{
		parent::init();

		$forms = $this->getAllVisualElements();

		if (!empty($forms)) {
			foreach ($forms as $form) {
				if (!$form->inited()) {
					$form->init();
				}
			}
		}
	}

	public function run()
	{
		parent::init();

		$forms = $this->getAllVisualElements();
		
		if (!empty($forms)) {
			foreach ($forms as $form) {
				$form->run();
			}
		}
		
	}

	public function shutdown()
	{
		parent::shutdown();
	}

	public function toString()
	{
		$object = parent::toString();
		$forms = $this->getAllVisualElements();

		if (!empty($forms)) {
			foreach ($forms as $form) {
				//$data[$form->getPrefix()] = $form->toString();
				$object->{$form->getPrefix()} = $form->toString();
			}
		}

		unset($forms);
		$object->object = $this;

		return $object;
	}

	public function getTemplate()
	{
		return PTA_TemplateEngine::getInstance()->getTemplate($this->getPrefix());
	}

	public function getTemplateVars()
	{
		return $this->getTemplate()->getVars();
	}

	public function setTemplate($tpl)
	{
		if (is_string($tpl)) {
			try {
				PTA_TemplateEngine::getInstance()->registerTemplate($this->getPrefix(), $tpl);
				$this->setVar('tpl', $tpl);
			} catch (Zend_Exception $e) {
				echo $e->getMessage();
			}
		}
	}

	public function addVisual($visual)
	{
		if ($this->inited()) {
			$visual->init();
		}

		if ($this->runned()) {
			$visual->run();
		}
		
		$this->_visuals[$visual->getPrefix()] = $visual;
	}

	public function getVisual($prefix)
	{
		return (!empty($this->_visuals[$prefix]) ? $this->_visuals[$prefix] : null);
	}

	public function getAllVisualElements()
	{
		return $this->_visuals;
	}

	/**
	 * return module URL
	 *
	 * @return string
	 */
	public function getModuleUrl()
	{
		return $this->getVar('url');
	}

	/**
	 * set module URL
	 *
	 * @param string $url
	 */
	public function setModuleUrl($url)
	{
		$this->setVar('url', $url);
	}

	public function setFilterData($data = array())
	{
		$data = (array)$data;
		$prefix = $this->getPrefix();
/*
		$app = $this->getApp();
		$cookieName = 'filter[' . $prefix . ']';
		if (empty($data)) {
			$app->setCookie($cookieName . '[' . $prefix . ']', null, -10);
		} else {
			foreach ($data as $filterName => $filterValue) {
				$app->setCookie($cookieName . '[' . $filterName . ']', $filterValue);
			}
		}
*/
		$filterData = (array)$this->getVar('filterData');
		$filterData[$prefix] = $data;
		$this->setVar('filterData', $filterData);
	}
	
	public function getFilterData()
	{
		$data = $this->getVar('filterData');

		if (empty($data) && ($data = $this->getApp()->getCookie('filter'))) {
			$this->setVar('filterData', $data);
		}

		$prefix = $this->getPrefix();

		return (!empty($data[$prefix]) ? $data[$prefix] : array());
	}
}
