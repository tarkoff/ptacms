#!/bin/bash

cd ~/app/data/utils/miximport2

# Doenload compressed xml file
wget --output-document=advs_.plx "http://price.mixmarket.biz/yaml.plx?id=4294945418&list=1"
iconv -f windows-1251 -t UTF-8 advs_.plx > advs.plx
rm -f advs_.plx 
# mv advs_.plx advs.plx

# Parse xml file
php mix_truncate.php > mix_truncate.log
