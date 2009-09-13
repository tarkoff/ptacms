<?php
require './core/SitemapBuilder.php';

$builder = new SitemapBuilder();

$builder->setRootUrl('http://gelezka');
$builder->setSavePath('../../../sitemap.xml');
$builder->addAllowedExt(array('htm', 'html', 'php'));

$builder->build();
