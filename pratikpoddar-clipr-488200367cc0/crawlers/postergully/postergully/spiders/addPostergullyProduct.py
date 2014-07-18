import sys
import os
currDir = os.path.dirname(os.path.abspath(__file__))
parentDir = reduce( lambda x,y : x+'/'+y, currDir.split('/')[:-1])
grandParentDir = reduce( lambda x,y : x+'/'+y, parentDir.split('/')[:-1])

sys.path.insert(0, currDir)
sys.path.insert(0, parentDir)
sys.path.insert(0, grandParentDir)
# scrapy imports
from scrapy.http import Request, TextResponse, Response
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from crawlerUtils import parseLinkForSite

# local imports
from postergully.items import CliprItem
from postergully_spider import parser
from datetime import datetime, date, timedelta

def getUniqueId(response):
	site = HtmlXPathSelector(response)
	currId = site.select('//select[@id="product-select"]/option/@value').extract()[0]
	return currId

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "postergully", parser, getUniqueId, givenLink, forceRefresh )
