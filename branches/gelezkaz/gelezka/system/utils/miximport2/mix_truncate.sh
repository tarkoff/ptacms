#!/bin/bash

# Doenload compressed xml file
wget --output-document=advs.plx "http://price.mixmarket.biz/yaml.plx?id=4294945418&list=1"

# Parse xml file
php mix_truncate.php > mix_truncate.log
