<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR
	. get_include_path()
);

require 'Mix/Parser.php';

$parser = new Mix_Parser('advs.plx');

$parser->init();
$parser->clearTables();
$parser->disableKeys();
$parser->parse();
$parser->enableKeys();
