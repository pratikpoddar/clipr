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
from donebynone.items import CliprItem
from donebynone_spider import parser


def getUniqueId(response):
	site = HtmlXPathSelector(response)
	return int((site.select('//span[@id="skudiv"]/text()').extract()[0]).split('-')[1])

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "donebynone", parser, getUniqueId, givenLink, forceRefresh )
