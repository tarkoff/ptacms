<?php
require dirname(__FILE__) . '/core/SitemapBuilder.php';

$builder = new SitemapBuilder();

$builder->setRootUrl('http://www.gelezka.net');
$builder->setSavePath(dirname(__FILE__) . '/../../../');
$builder->addAllowedExt(array('htm', 'html', 'php'));

$builder->build();
