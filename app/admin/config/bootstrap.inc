<?php
define('PTA_ROOT_PATH', rtrim(realpath(dirname(dirname(dirname(dirname(__FILE__))))), DIRECTORY_SEPARATOR));

set_include_path(
		implode(
			PATH_SEPARATOR,
			array(
				PTA_ROOT_PATH . '/library',
				PTA_ADMIN_PATH
			)
		) . PATH_SEPARATOR . get_include_path()
);

require_once PTA_ROOT_PATH . '/app/admin/config/server.inc';
require_once PTA_ROOT_PATH . '/app/admin/config/config.inc';
require_once PTA_ROOT_PATH . '/library/Zend/Loader/Autoloader.php';
require_once SMARTY_DIR . 'Smarty.class.php';
require_once PTA_ROOT_PATH . '/app/admin/config/Initialize.php';

Initialize::startInit();

defined('PTA_DESIGN_THEME') || define('PTA_DESIGN_THEME', 'default');