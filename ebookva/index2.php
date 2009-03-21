<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: index.php 25 2009-03-16 21:32:59Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
ob_start();
error_reporting(E_ALL);
ini_set('dipslay_errors', 1);
var_dump($_SERVER['REQUEST_URI']);
if (
	!empty($_SERVER['REQUEST_URI']) 
	&& (strpos('admin', strtolower($_SERVER['REQUEST_URI'])) <= 1)
) {
	include_once './app/admin/index.php';
	$app = new adminApp();
} else {
	require_once './app/site/index.php';
	$app = new siteApp();
}
