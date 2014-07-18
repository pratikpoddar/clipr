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
from shaze.items import CliprItem
from shaze_spider import parser
from datetime import datetime, date, timedelta


def getUniqueId(response):
	return int((response.url.split('/p/')[1]).split('/')[0])

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "shaze", parser, getUniqueId, givenLink, forceRefresh )

