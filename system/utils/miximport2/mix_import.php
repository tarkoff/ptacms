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
$select->where('ADVERTIZERS_UPDATED = 0')->limit(1);

foreach ($db->fetchPairs($select) as $shopId => $shopUrl) {
	$xmlRead = fopen($shopUrl, "r");
	$xmlWrite = fopen('mixml.plx', "w");
	if ($xmlRead && $xmlWrite) {
		while (!feof($xmlRead)) {
			//$buffer = iconv('Windows-1251', 'UTF-8', fgets($xmlRead, 4096));
			$buffer = fgets($xmlRead, 4096);
			fwrite($xmlWrite, $buffer);
		}
	}
	fclose($xmlRead);
	fclose($xmlWrite);

	$parser = new Mix_Parser('mixml.plx');
	$parser->init();
//	$parser->disableKeys();
	if ($parser->parse()) {
		$db->query('UPDATE MIXMART_ADVERTIZERS SET ADVERTIZERS_UPDATED = 1 WHERE ADVERTIZERS_ID = ' . $shopId);
	}
//	$parser->enableKeys();
	unset($parser);

	var_dump($shopUrl);
}

