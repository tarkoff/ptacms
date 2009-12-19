#!/bin/bash

svn up
chmod -R 0755 .
chmod -R 0777 public/content
chmod -R 0764 public/timthumb/cache
chmod -R 0764 system/cache/admin/templates_c
chmod -R 0764 system/cache/site/templates_c
