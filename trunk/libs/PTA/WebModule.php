<?php

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
	        	$form->init();
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
	    
	    $data = array();
	    if (!empty($forms)) {
	        foreach ($forms as $form) {
	        	//$data[$form->getPrefix()] = $form->toString();
	        	$object->{$form->getPrefix()} = $form->toString();
	        }
	    }
	    
//	    $object->data = $data;
	    
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
	    if ($this->isInited()) {
	        $visual->init();	        	    
	    }
	    
	    if ($this->isRunned()) {
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
		
}
