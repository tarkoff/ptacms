<?php
define('APPDEBUG', true);

if (!empty($_SERVER['HTTP_HOST'])) {
	define('BASEURL', 'http://' . $_SERVER['HTTP_HOST']);
	define('ADMINURL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');
} else {
	define('BASEURL', 'http://ebookva.net');
	define('ADMINURL', 'http://ebookva.net/admin');
}

if (empty($rootPath)) {
	define('ROOTPATH', @$_SERVER['DOCUMENT_ROOT']);
} else {
	define('ROOTPATH', $rootPath);
}
define('SITEID', 1);

define('SMARTY_DIR', ROOTPATH . '/library/Smarty/libs/');

define('PUBLICPATH', ROOTPATH . '/public');
define('CONTENTPATH', PUBLICPATH . '/content');
define('CONTENTPHOTOSPATH', CONTENTPATH . '/images');
define('ADMINPATH', ROOTPATH . '/app/admin');

define('CACHEPATH', ROOTPATH . '/system/cache/admin');
define('LOGSPATH', ROOTPATH . '/system/logs/admin');

define('DESIGN_NAME', 'default');
define('DESIGNDIR', PUBLICPATH . '/style/admin/' . DESIGN_NAME);
define('DESIGNURL', BASEURL . '/public/style/admin/' . DESIGN_NAME);
define('CSSURL', DESIGNURL . '/css');
define('IMAGESURL', DESIGNURL . '/img');
define('JSURL', BASEURL . '/public/js');
define('JQUERYURL', JSURL . '/jquery');

define('TEMPLATESDIR', DESIGNDIR . '/templates/');
define('TEMPLATES_CDIR', CACHEPATH . '/templates_c/');

//2 days | 3600 - 1hour
define('COOKIE_EXPIRE_TIME', 172800);

//define('PROFILER_TIME', 1);
