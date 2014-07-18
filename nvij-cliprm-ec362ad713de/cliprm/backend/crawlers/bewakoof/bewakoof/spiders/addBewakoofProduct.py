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
from bewakoof.items import CliprItem
from bewakoof_spider import parser


def getUniqueId(response):
	return ((response.url.split('/product/')[1]).split('/')[0]).split('?')[0]

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "bewakoof", parser, getUniqueId, givenLink, forceRefresh )


