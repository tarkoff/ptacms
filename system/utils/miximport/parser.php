<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR 
	. get_include_path() 
);

require './lib/MixParser.php';

define('MIX_XML_FILE', 'mixml.plx');

$parser = new MixParser(MIX_XML_FILE);

$parser->init();
$parser->clearTables();
$parser->parse();
