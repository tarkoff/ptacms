#!/bin/bash

# Download compressed xml file
# wget --output-document=mixml_.plx.gz "http://mixmarket.biz/mixml.plx?id=4294945418&zip=1"

# unzip xml file
# gunzip -f mixml_.plx.gz
# iconv -f windows-1251 -t UTF-8 mixml_.plx > mixml.plx

# Parse xml file
php mix_import.php > mix_import.log
php mix_port.php > mix_port.log
