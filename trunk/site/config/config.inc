<?php
define('ROOTPATH', $rootPath);
define('SITEID', 2);

define('SMARTY_DIR', ROOTPATH . '/libs/Smarty/libs/');

define('PUBLICPATH', ROOTPATH . '/public');
define('SITEPATH', ROOTPATH . '/site');
define('STYLESDIR', PUBLICPATH . '/styles/site/main');

define('BASEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('CSSURL', BASEURL . '/public/styles/site/main/css');
define('IMAGESURL', BASEURL . '/public/styles/site/main/img');

define('IMAGESDIR', STYLESDIR . '/img');
define('CSSDIR', STYLESDIR . '/css');
define('TEMPLATESDIR', STYLESDIR . '/templates');
define('TEMPLATES_CDIR', STYLESDIR . '/templates_c');
