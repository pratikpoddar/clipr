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
from flipkart.items import CliprItem
from flipkart_spider import parser, parserSmallImage
from datetime import datetime, date, timedelta

def getUniqueId(response):
	url = response.url
	if url.find("/p/") >= 0:
		currId = -1
		if url.find('?pid=') >= 0:
			currId = (url.split('?pid=')[1].split('&'))[0]
		elif url.find('&id=') >= 0:
			currId = (url.split('&pid=')[1].split('&'))[0]
		return currId
	else:
		raise Exception("not a product")

def parseLink(givenLink = "", forceRefresh = False):
	if forceRefresh:
		return parseLinkForSite( "flipkart", parser, getUniqueId, givenLink, forceRefresh )
	else:
		return parseLinkForSite( "flipkart", parserSmallImage, getUniqueId, givenLink, forceRefresh )
