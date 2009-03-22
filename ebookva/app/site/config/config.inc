<?php
define('APPDEBUG', true);

if (!empty($_SERVER['HTTP_HOST'])) {
	define('BASEURL', 'http://' . $_SERVER['HTTP_HOST']);
} else {
	define('BASEURL', 'http://ebookva.net');
}

if (empty($rootPath)) {
	define('ROOTPATH', @$_SERVER['DOCUMENT_ROOT']);
} else {
	define('ROOTPATH', $rootPath);
}
define('SITEID', 2);

define('SMARTY_DIR', ROOTPATH . '/library/Smarty/libs/');

define('PUBLICPATH', ROOTPATH . '/public');
define('SITEPATH', ROOTPATH . '/app/site');

define('CACHEPATH', ROOTPATH . '/system/cache/site');
define('LOGSPATH', ROOTPATH . '/system/logs/site');

define('DESIGN_NAME', 'default');

define('DESIGNDIR', PUBLICPATH . '/style/site/' . DESIGN_NAME);
define('DESIGNURL', BASEURL . '/public/style/site/' . DESIGN_NAME);
#define('DESIGNURL', '/public/style/site/' . DESIGN_NAME);


define('CSSURL', DESIGNURL . '/css');
define('IMAGESURL', DESIGNURL . '/img');
define('JSURL', BASEURL . '/public/js');
define('JQUERYURL', JSURL . '/jquery');

define('TEMPLATESDIR', DESIGNDIR . '/templates/');
define('TEMPLATES_CDIR', CACHEPATH . '/templates_c/');

//2 days | 3600 - 1hour
define('COOKIE_EXPIRE_TIME', 172800);

//define('PROFILER_TIME', 1);
