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

class PTA_Smarty_Extention
{
	private $_smarty;

	public function __construct($smarty)
	{
		if (!($smarty instanceof Smarty)) {
			return false;
		}

		$this->_smarty = $smarty;

		$this->registerBlocks();
		$this->registerFunctions();
	}

	public function registerBlocks()
	{
		$this->_smarty->register_block("translate", array($this, "do_translation"));
	}

	public function registerFunctions()
	{
		$this->_smarty->register_function('pta_const', array($this, 'ptaConst'));
	}

	function do_translation ($params, $content, &$smarty, &$repeat)
	{
		if (isset($content)) {
			$lang = $params['lang'];
			// выполняем перевод $content
			return $translation;
		}
	}
	
	public function ptaConst($params)
	{
		if (!empty($params['name']) && defined($params['name'])) {
			if (empty($params['to'])) {
				if (defined($params['name'])) {
					return constant($params['name']);
				}
			} else {
				if (defined($params['name'])) {
					$this->_smarty->assign($params['to'], constant($params['name']));
				}
			}
		}
		return null;
	}
}
