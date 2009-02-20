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
define('ADMINPATH', ROOTPATH . '/app/site');

define('DESIGN_NAME', 'default');

define('DESIGNDIR', PUBLICPATH . '/style/admin/' . DESIGN_NAME);
define('DESIGNURL', BASEURL . '/public/style/admin/' . DESIGN_NAME);

define('CSSURL', DESIGNURL . '/css');
define('IMAGESURL', DESIGNURL . '/img');

define('TEMPLATESDIR', DESIGNDIR . '/templates/');
define('TEMPLATES_CDIR', DESIGNDIR . '/templates_c/');

//define('PROFILER_TIME', 1);
