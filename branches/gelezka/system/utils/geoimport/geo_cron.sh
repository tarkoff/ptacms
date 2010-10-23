#!/bin/bash

# Doenload compressed xml file
#wget --output-document=mixml.plx.gz "http://mixmarket.biz/mixml.plx?id=4294945418&zip=1"

# unzip xml file
#gunzip -f mixml.plx.gz

iconv -f Windows-1251 -t UTF-8 < cidr_ru_block.txt > cidr_ru_block_utf8.txt

# Parse xml file
php geo_import.php > geo_import.log
