#!/bin/bash
_now=$(date +"%m_%d_%Y")
_file="/var/www/errors/error_$_now.txt"
cat /var/www/logFiles/php_error.log  | grep $(date +"%d-%h-%Y") | grep -v "Unknown column 'img' in 'where clause"> "$_file"
mailx -s "Summary Error Clipr" pratik.phodu@gmail.com,nikhil007vij@gmail.com <"$_file"
