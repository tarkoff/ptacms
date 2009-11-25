#!/bin/bash

CURDATE=$(date +"%Y%m%d")
DUMPDIR="/srv/www/gelezka/system/backups/daylly"
CONTENTDIR="/srv/www/gelezka/public/content"

ONEMONTHAGO=$(date --date='1 month ago' +"%Y%m%d")
for i in $( ls $DUMPDIR); do
	if [ "$i" -lt "$ONEMONTHAGO" ] ; then
		rm -rf $DUMPDIR/$i
	fi
done

DUMPDIR="/srv/www/gelezka/system/backups/daylly/$CURDATE"

mkdir -p $DUMPDIR
mysqldump --opt --user=root --password= --databases GELEZKA > $DUMPDIR/database.sql
zip -r $DUMPDIR/content.zip $CONTENTDIR
zip -r $DUMPDIR/database.zip $DUMPDIR/database.sql
rm -f $DUMPDIR/database.sql
