<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/core'
	. PATH_SEPARATOR
	. get_include_path()
);

require 'SitemapBuilder.php';

$builder = new SitemapBuilder();
var_dump(dirname(__FILE__) . '/config.xml');
$builder->setRootUrl('http://www.gelezka.net');
$builder->setConfigFile(dirname(__FILE__) . '/config.xml');
$builder->setWorkMode(SitemapBuilder::WORK_MODE_DB);
$builder->setSavePath(dirname(__FILE__) . '/../../../');
$builder->addAllowedExt(array('htm', 'html', 'php'));

$builder->build();
