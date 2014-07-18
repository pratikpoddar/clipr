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
from gloob.items import CliprItem
from gloob_spider import parser


def getUniqueId(response):
	site = HtmlXPathSelector(response)
	buylink = site.select('//form[@id="product_addtocart_form"]/@action').extract()[0]
	tokens = buylink.split('/')
	assert(len(tokens) > 2)
	return tokens[len(tokens)-2]

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "gloob", parser, getUniqueId, givenLink, forceRefresh )


