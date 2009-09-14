<?php
define('PTA_ROOT_PATH', rtrim(realpath(dirname(dirname(dirname(dirname(__FILE__))))), DIRECTORY_SEPARATOR));

require_once 'server.inc';
require_once 'config.inc';
require_once 'Initialize.php';

require_once PTA_ROOT_PATH . '/library/Zend/Loader.php';
require_once(SMARTY_DIR . 'Smarty.class.php');

set_include_path(
		implode(PATH_SEPARATOR,
				array(
					PTA_ROOT_PATH,
					PTA_ROOT_PATH . '/library',
					PTA_SITE_PATH
				)
		) 
		. PATH_SEPARATOR . get_include_path()
);

Initialize::startInit();

