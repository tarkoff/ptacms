#!/bin/bash

cd ~/app/data/utils/backup

CURDATE=$(date +"%Y%m%d")
DUMPDIR="./daylly"
CONTENTDIR="/home/gelezkan/public_html/images/catalog"

ONEMONTHAGO=$(date --date='7 days ago' +"%Y%m%d")
for i in $( ls $DUMPDIR); do
	if [ "$i" -lt "$ONEMONTHAGO" ] ; then
		rm -rf $DUMPDIR/$i
	fi
done

DUMPDIR="$DUMPDIR/$CURDATE"

mkdir -p $DUMPDIR
mysqldump --opt --user=gelezkan_gz --password=Nkq1itDqdMWQ --databases gelezkan_gz > $DUMPDIR/database.sql
zip -r $DUMPDIR/content.zip $CONTENTDIR
zip -r $DUMPDIR/database.zip $DUMPDIR/database.sql
rm -f $DUMPDIR/database.sql
