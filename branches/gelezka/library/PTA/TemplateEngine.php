<?php
/**
 *  PTA App Template Engine
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_TemplateEngine extends PTA_Object
{
	private static $_instance;	
	private $_smarty=null;
	private $_tpls = array();
	private $_indexTemplate;

	private function __construct()
	{
		$this->setPrefix('templateEngine');
	}

	private function __clone() {}

	public static function getInstance()
	{
		if (!self::$_instance instanceof self) {
			self::$_instance = new self;
		}
		
		return self::$_instance;
	}

	public function init()
	{
		$this->_smarty = &Zend_Registry::get('Smarty');
		$smartyExtention = new PTA_Smarty_Extention($this->_smarty);
	}

	public function registerTemplate($prefix, $tplFile)
	{
		if(!isset($this->_tpls[$prefix])){
			$tpl = new PTA_Template($prefix, $tplFile);
			$this->_tpls[$prefix] = $tpl;
		}
	}

	public function display()
	{
		if(empty($this->_tpls)) {
			return false;
		}

		$app = $this->getApp();

		foreach ($app->getModules() as $module) {
			$toStringModule = $module->toString();
			if ($module->isActive()) {
				$this->_smarty->assign_by_ref('activeModule', $toStringModule);
			}
			$this->_smarty->assign_by_ref($module->getPrefix(), $toStringModule);
			unset($toStringModule);
		}

		$this->_smarty->assign_by_ref($app->getPrefix(), $app->toString());
		$this->_smarty->display($app->getTemplate()->getFile());
		return true;
	}

	public function getTemplate($prefix)
	{
		if(isset($this->_tpls[$prefix])) {
			return $this->_tpls[$prefix];
		}
		
		return false;
	}

}
