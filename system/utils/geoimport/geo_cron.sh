#!/bin/bash

# Doenload compressed xml file
wget --output-document=geo.tar.gz "http://ipgeobase.ru/files/db/Main/db_files.tar.gz"

# unzip xml file
gunzip -c geo.tar.gz | tar xvf -

iconv -f Windows-1251 -t UTF-8 < cidr_ru_block.txt > cidr_ru_block_utf8.txt

# Parse xml file
php geo_import.php > geo_import.log
