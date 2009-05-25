<?php
define('PTA_APP_DEBUG', true);

if (!empty($_SERVER['HTTP_HOST'])) {
	define('PTA_BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);
} else {
	define('PTA_BASE_URL', 'http://gelezka.info');
}

if (empty($rootPath)) {
	define('PTA_ROOT_PATH', @$_SERVER['DOCUMENT_ROOT']);
} else {
	define('PTA_ROOT_PATH', $rootPath);
}
define('PTA_SITE_ID', 2);

define('SMARTY_DIR', PTA_ROOT_PATH . '/library/Smarty/libs/');

define('PTA_PUBLIC_PATH', PTA_ROOT_PATH . '/public');
define('PTA_SITE_PATH', PTA_ROOT_PATH . '/app/site');

define('PTA_CACHE_PATH', PTA_ROOT_PATH . '/system/cache/site');
define('PTA_LOG_PATH', PTA_ROOT_PATH . '/system/logs/site');

define('PTA_DESIGN_NAME', 'default');

define('PTA_DESIGN_PATH', PTA_PUBLIC_PATH . '/style/site/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_URL', PTA_BASE_URL . '/public/style/site/' . PTA_DESIGN_NAME);
#define('PTA_DESIGN_URL', '/public/style/site/' . PTA_DESIGN_NAME);


define('PTA_CSS_URL', PTA_DESIGN_URL . '/css');
define('PTA_IMAGES_URL', PTA_DESIGN_URL . '/img');
define('PTA_JS_URL', PTA_BASE_URL . '/public/js');
define('PTA_JS_JQUERY_URL', PTA_JS_URL . '/jquery');
define('PTA_THUMB_URL', PTA_BASE_URL . '/public/timthumb/timthumb.php');

define('PTA_TEMPLATES_DIR', PTA_DESIGN_PATH . '/templates/');
define('PTA_TEMPLATES_CDIR', PTA_CACHE_PATH . '/templates_c/');

//2 days | 3600 - 1hour
define('PTA_COOKIE_EXPIRE_TIME', 172800);

//define('PTA_PROFILER_TIME', 1);
