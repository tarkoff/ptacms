<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR 
	. get_include_path() 
);

require 'Mix/Port.php';

$parser = new Mix_Port();

$parser->init();
$parser->portCategories();
$parser->portBrands();
$parser->portOffers();