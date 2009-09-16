<?php
if (!empty($_SERVER['HTTP_HOST'])) {
	define('PTA_BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);
	if (false !== strpos( $_SERVER['HTTP_HOST'], 'admin.')) {
		define('PTA_ADMIN_URL', 'http://' . $_SERVER['HTTP_HOST']);
	} else {
		define('PTA_ADMIN_URL', 'http://www.admin.' . ltrim($_SERVER['HTTP_HOST'], 'www.'));
	}
} else {
	define('PTA_BASE_URL', 'http://www.gelezka.net');
	define('PTA_ADMIN_URL', 'http://www.admin.gelezka.net');
}

if (!defined('PTA_ROOT_PATH')) {
	define('PTA_ROOT_PATH', realpath(dirname(dirname(dirname(__FILE__)))));
}

define('SMARTY_DIR', PTA_ROOT_PATH . '/library/Smarty/libs/');
define('PTA_PUBLIC_PATH', PTA_ROOT_PATH . '/public');
define('PTA_GENERIC_TEMPLATES_PATH', PTA_PUBLIC_PATH . '/style/admin/_generic');

define('PTA_SITE_PATH', PTA_ROOT_PATH . '/app/site');
define('PTA_ADMIN_PATH', PTA_ROOT_PATH . '/app/admin');

define('PTA_CONTENT_PATH', PTA_PUBLIC_PATH . '/content');
define('PTA_CONTENT_PHOTOS_PATH', PTA_CONTENT_PATH . '/images');
define('PTA_CONTENT_URL', '/public/content');
define('PTA_CONTENT_PHOTOS_URL', PTA_CONTENT_URL . '/images');

define('PTA_JS_URL', '/public/js');
define('PTA_JS_JQUERY_URL', PTA_JS_URL . '/jquery');
define('PTA_THUMB_URL', '/public/timthumb/timthumb.php');

//define('PTA_RECAPTCHA_PRIVATE_KEY', '6LeKcgcAAAAAAJJ3hcJOJXcUDFBa4YJf5ZGa8hl2');
//define('PTA_RECAPTCHA_PUBLIC_KEY', '6LeKcgcAAAAAAHlhPZZh4OwaaMztDXekLRvbOx5u');

define('PTA_CAPTCHA_WRITE_UNSWER', 'пара,para');
//2 days | 3600 - 1hour
define('PTA_COOKIE_EXPIRE_TIME', 172800);
define('PTA_PROFILER_TIME', 0.01);
