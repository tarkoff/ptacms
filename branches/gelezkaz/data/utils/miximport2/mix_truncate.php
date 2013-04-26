<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR
	. get_include_path()
);

require 'Mix/Parser.php';

$parser = new Mix_Parser('advs_.plx');

$parser->init();
$parser->disableKeys();
$parser->clearTables();
$parser->parse();
$parser->enableKeys();

$db = $parser->getDB();
$db->query('UPDATE MIXMART_ADVERTIZERS SET ADVERTIZERS_UPDATED = 0');

require 'Mix/Port.php';

$parser = new Mix_Port();

$parser->init();
$parser->portCategories();
$parser->portBrands();
