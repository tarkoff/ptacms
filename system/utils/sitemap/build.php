<?php
require dirname(__FILE__) . '/core/SitemapBuilder.php';

$builder = new SitemapBuilder();

$builder->setRootUrl('http://gelezka.net');
$builder->setSavePath(dirname(__FILE__) . '/../../../sitemap.xml');
$builder->addAllowedExt(array('htm', 'html', 'php'));

$builder->build();
