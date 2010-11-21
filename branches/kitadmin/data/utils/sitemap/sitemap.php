<?php
set_include_path(
	rtrim(realpath(dirname(dirname(dirname(dirname(__FILE__))))), '/') . '/library'
	. PATH_SEPARATOR
	. get_include_path()
);

//var_dump(get_include_path());
$container = new Zend_Navigation();
