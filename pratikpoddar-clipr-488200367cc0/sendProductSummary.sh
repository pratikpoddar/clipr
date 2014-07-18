#!/bin/bash
_now=$(date +"%m_%d_%Y")
_file="/var/www/summary/product_summary_$_now.txt"
cd /var/www/clipr
python productFetchSummary.py > "$_file"
mailx -s "Summary Product Fetch Clipr" pratik.phodu@gmail.com,nikhil007vij@gmail.com <"$_file"

_file2="/var/www/summary/product_summary_beta_$_now.txt"
cd /var/www/beta/clipr
python productFetchSummary.py > "$_file2"
mailx -s "Summary Product Fetch Clipr- Beta" pratik.phodu@gmail.com,nikhil007vij@gmail.com <"$_file2"

