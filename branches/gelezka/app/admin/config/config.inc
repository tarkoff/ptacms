<?php
define('PTA_APP_DEBUG', true);

if (!empty($_SERVER['HTTP_HOST'])) {
	define('PTA_BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);
	define('PTA_ADMIN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');
} else {
	define('PTA_BASE_URL', 'http://ebookva.net');
	define('PTA_ADMIN_URL', 'http://ebookva.net/admin');
}

if (empty($rootPath)) {
	define('PTA_ROOT_PATH', @$_SERVER['DOCUMENT_ROOT']);
} else {
	define('PTA_ROOT_PATH', $rootPath);
}
define('PTA_SITE_ID', 1);

define('SMARTY_DIR', PTA_ROOT_PATH . '/library/Smarty/libs/');

define('PTA_PUBLIC_PATH', PTA_ROOT_PATH . '/public');
define('PTA_CONTENT_PATH', PTA_PUBLIC_PATH . '/content');
define('PTA_CONTENT_PHOTOS_PATH', PTA_CONTENT_PATH . '/images');
define('PTA_ADMIN_PATH', PTA_ROOT_PATH . '/app/admin');

define('PTA_CONTENT_URL', PTA_BASE_URL . '/public/content');
define('PTA_CONTENT_PHOTOS_URL', PTA_CONTENT_URL . '/images');

define('PTA_CACHE_PATH', PTA_ROOT_PATH . '/system/cache/admin');
define('PTA_LOG_PATH', PTA_ROOT_PATH . '/system/logs/admin');

define('PTA_DESIGN_NAME', 'default');
define('PTA_DESIGN_PATH', PTA_PUBLIC_PATH . '/style/admin/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_URL', PTA_BASE_URL . '/public/style/admin/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_CSS_URL', PTA_DESIGN_URL . '/css');
define('PTA_DESIGN_IMAGES_URL', PTA_DESIGN_URL . '/img');
define('PTA_JS_URL', PTA_BASE_URL . '/public/js');
define('PTA_JS_JQUERY_URL', PTA_JS_URL . '/jquery');
define('PTA_THUMB_URL', PTA_BASE_URL . '/public/timthumb/timthumb.php');

define('PTA_TEMPLATES_DIR', PTA_DESIGN_PATH . '/templates/');
define('PTA_TEMPLATES_CDIR', PTA_CACHE_PATH . '/templates_c/');

//2 days | 3600 - 1hour
define('PTA_COOKIE_EXPIRE_TIME', 172800);
define('PTA_PROFILER_TIME', 0.01);
