#!/bin/bash

CURDATE=$(date +"%Y%m%d")
DUMPDIR="/home/gelezkan/public_html/system/backups/daylly"
CONTENTDIR="/home/gelezkan/public_html/public/content"

ONEMONTHAGO=$(date --date='7 days ago' +"%Y%m%d")
for i in $( ls $DUMPDIR); do
	if [ "$i" -lt "$ONEMONTHAGO" ] ; then
		rm -rf $DUMPDIR/$i
	fi
done

DUMPDIR="$DUMPDIR/$CURDATE"

mkdir -p $DUMPDIR
mysqldump --opt --user=gelezkan_gelezka --password=ZaWZ5E9Aily3 --databases gelezkan_gelezka > $DUMPDIR/database.sql
zip -r $DUMPDIR/content.zip $CONTENTDIR
zip -r $DUMPDIR/database.zip $DUMPDIR/database.sql
rm -f $DUMPDIR/database.sql
