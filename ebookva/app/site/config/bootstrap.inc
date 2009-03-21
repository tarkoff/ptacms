<?php
if (isset($_SERVER['DOCUMENT_ROOT'])) {
	$rootPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);
} else {
	$rootPath = rtrim(realpath(dirname(dirname(dirname(__FILE__)))), DIRECTORY_SEPARATOR);	
}

require_once 'server.inc';
require_once 'config.inc';
require_once 'Initialize.php';

require_once $rootPath . '/library/Zend/Loader.php';
require_once(SMARTY_DIR . 'Smarty.class.php');

set_include_path(
		implode(PATH_SEPARATOR,
				array(
					$rootPath,
					$rootPath . '/library',
					SITEPATH
				)
		) 
		. PATH_SEPARATOR . get_include_path()
);

Initialize::startInit();

