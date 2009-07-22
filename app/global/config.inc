<?php
if (!empty($_SERVER['HTTP_HOST'])) {
	define('PTA_BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);
	define('PTA_ADMIN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');
} else {
	define('PTA_BASE_URL', 'http://gelezka.net');
	define('PTA_ADMIN_URL', 'http://gelezka.net/admin');
}

if (empty($_SERVER['DOCUMENT_ROOT'])) {
	define('PTA_ROOT_PATH', realpath(dirname(dirname(dirname(__FILE__)))));
} else {
	define('PTA_ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}

define('SMARTY_DIR', PTA_ROOT_PATH . '/library/Smarty/libs/');
define('PTA_PUBLIC_PATH', PTA_ROOT_PATH . '/public');

define('PTA_SITE_PATH', PTA_ROOT_PATH . '/app/site');
define('PTA_ADMIN_PATH', PTA_ROOT_PATH . '/app/admin');

define('PTA_CONTENT_PATH', PTA_PUBLIC_PATH . '/content');
define('PTA_CONTENT_PHOTOS_PATH', PTA_CONTENT_PATH . '/images');
define('PTA_CONTENT_URL', PTA_BASE_URL . '/public/content');
define('PTA_CONTENT_PHOTOS_URL', PTA_CONTENT_URL . '/images');

define('PTA_JS_URL', PTA_BASE_URL . '/public/js');
define('PTA_JS_JQUERY_URL', PTA_JS_URL . '/jquery');
define('PTA_THUMB_URL', PTA_BASE_URL . '/public/timthumb/timthumb.php');

define('PTA_RECAPTCHA_PRIVATE_KEY', '6LeKcgcAAAAAAJJ3hcJOJXcUDFBa4YJf5ZGa8hl2');
define('PTA_RECAPTCHA_PUBLIC_KEY', '6LeKcgcAAAAAAHlhPZZh4OwaaMztDXekLRvbOx5u');

//2 days | 3600 - 1hour
define('PTA_COOKIE_EXPIRE_TIME', 172800);
define('PTA_PROFILER_TIME', 0.01);
