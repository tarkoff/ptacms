<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Singleton.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_Singleton {
	private static $instance;

  	private function __construct() {
  	}
 
  	private function __clone() {
  	}

  	public static function getInstance() {
  		if (!self::$instance instanceof self) {
  			self::$instance = new self;
    	}
    return self::$instance;
  }

}