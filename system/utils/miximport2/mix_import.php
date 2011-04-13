<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/lib'
	. PATH_SEPARATOR
	. get_include_path()
);

require 'Mix/Parser.php';

$config = new Zend_Config_Xml('config.xml');
!empty($config) || trigger_error('Config file not found.', E_USER_ERROR);

$db = Zend_Db::factory($config->database);
is_object($db) || trigger_error('Database connection error.', E_USER_ERROR);
$db->query('SET NAMES UTF8');

$select = $db->select()->from('MIXMART_ADVERTIZERS', array('ADVERTIZERS_ID', 'ADVERTIZERS_URL'));
$select->where('ADVERTIZERS_UPDATED = 0');

foreach ($db->fetchAssoc($select) as $shopId => $shopUrl) {
	var_dump($shopUrl);
}
exit(0);
$parser = new Mix_Parser('mixml.plx');

$parser->init();


//$parser->clearTables();
$parser->disableKeys();
$parser->parse();
$parser->enableKeys();
