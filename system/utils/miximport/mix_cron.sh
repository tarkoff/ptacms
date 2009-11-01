#!/bin/bash

# Doenload compressed xml file
wget --output-document=mixml.plx.gz "http://mixmarket.biz/mixml.plx?id=4294945418&zip=1"

# unzip xml file
gunzip -f mixml.plx.gz

# Parse xml file
php mix_import.php > mix_import.log
php mix_port.php > mix_port.log
