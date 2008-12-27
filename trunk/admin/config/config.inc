<?php
define('APPDEBUG', true);

define('ROOTPATH', $rootPath);
define('SITEID', 1);

define('SMARTY_DIR', ROOTPATH . '/libs/Smarty/libs/');

define('PUBLICPATH', ROOTPATH . '/public');
define('ADMINPATH', ROOTPATH . '/admin');

define('BASEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('ADMINURL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');
define('SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/site');
define('CSSURL', BASEURL . '/public/styles/admin/main/css');
define('IMAGESURL', BASEURL . '/public/styles/admin/main/img');

define('STYLESDIR', PUBLICPATH . '/styles/admin/main');
define('TEMPLATESDIR', STYLESDIR . '/templates');
define('TEMPLATES_CDIR', STYLESDIR . '/templates_c');

//define('PROFILER_TIME', 1);