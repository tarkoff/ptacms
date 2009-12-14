<?php
set_include_path(
	rtrim(realpath(dirname(__FILE__)), '/') . '/core'
	. PATH_SEPARATOR
	. get_include_path()
);

require dirname(__FILE__) . '/core/SitemapBuilder.php';

$builder = new SitemapBuilder();

$builder->setRootUrl('http://www.gelezka.net');
$builder->setWorkMode(SitemapBuilder::WORK_MODE_DB);
$builder->setSavePath(dirname(__FILE__) . '/../../../');
$builder->addAllowedExt(array('htm', 'html', 'php'));

$builder->build();
