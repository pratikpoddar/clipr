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
from adventure18.items import CliprItem
from adventure18_spider import parser



def getUniqueId(response):
	site = HtmlXPathSelector(response)
	currId = site.select('//div[@id="vmMainPage"]/table/tbody/tr/td/div[@style="float:left;"]/text()').extract()
	currId = filter(lambda x: x.strip() != "", currId)
	return currId[0].strip()

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "adventure18", parser, getUniqueId, givenLink, forceRefresh )

