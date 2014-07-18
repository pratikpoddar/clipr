#!/bin/bash
_now=$(date +"%m_%d_%Y")
_file="/var/www/summary/summary_$_now.txt"
cd /var/www/clipr
python executiveSummary.py > "$_file"
mailx -s "Summary Executive Clipr - Daily" pratik.phodu@gmail.com,nikhil007vij@gmail.com <"$_file"

