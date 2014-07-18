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
from fabfurnish.items import CliprItem
from fabfurnish_spider import parser


def getUniqueId(response):
	site = HtmlXPathSelector(response)
	prodId = site.select('//input[@id="configSku"]/@value').extract()[0]
	prodId2Raw = site.select('//div[@class="l-sidebar"]/div[@data-no_cod and contains(id, prodId)]/@id').extract()[0]
	prodId2Trimmed = prodId2Raw.split(prodId)
	return (prodId + prodId2Trimmed[len(prodId2Trimmed)-1])

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "fabfurnish", parser, getUniqueId, givenLink, forceRefresh )


