<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR 
	. get_include_path() 
);

require './lib/MixParser.php';

//$file = 'http://mixmarket.biz/mixml.plx?id=4294945418';
$file = 'mixml.plx';

$parser = new MixParser($file);

//$parser->setLoadMode(MixParser::LOAD_LOCAL);
$parser->parse();
