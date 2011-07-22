<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR
	. get_include_path()
);

require 'Geo/Parser.php';

$parser = new Geo_Parser('cidr_ru_block_utf8.txt');

$parser->init();
$parser->clearTables();
//$parser->disableKeys();
$parser->parse();
//$parser->enableKeys();
