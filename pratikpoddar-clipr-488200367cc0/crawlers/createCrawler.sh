#!/bin/bash

mkdir $1
cp -r sampleCrawler/* $1/
cd $1/
find ./ -type f -exec sed -i s/"defaultCrawler"/"$1"/g {} +
mv zansaar/ $1
cd $1/spiders/
rm zansaar_spider.py 
cd ../../
scrapy list
cp ../afday/afday/spiders/afday_spider.py $1/spiders/
mv $1/spiders/afday_spider.py $1/spiders/$2 
find ./ -type f -exec sed -i s/"afday"/"$1"/g {} +
find ./ -type f -exec sed -i s/"INFO"/"DEBUG"/g {} +
