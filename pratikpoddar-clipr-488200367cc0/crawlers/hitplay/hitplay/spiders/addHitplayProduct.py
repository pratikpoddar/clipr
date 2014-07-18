import sys
import os
currDir = os.path.dirname(os.path.abspath(__file__))
parentDir = reduce( lambda x,y : x+'/'+y, currDir.split('/')[:-1])
grandParentDir = reduce( lambda x,y : x+'/'+y, parentDir.split('/')[:-1])

sys.path.insert(0, currDir)
sys.path.insert(0, parentDir)
sys.path.insert(0, grandParentDir)
# scrapy imports
import urlparse
from scrapy.http import Request, TextResponse, Response
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from crawlerUtils import parseLinkForSite

# local imports
from hitplay.items import CliprItem
from hitplay_spider import parser


def getUniqueId(response):
	url = response.url
	parsed = urlparse.urlparse(url)
	return urlparse.parse_qs(parsed.query)['id'][0]

def parseLink(givenLink = "", forceRefresh = False):
	return parseLinkForSite( "hitplay", parser, getUniqueId, givenLink, forceRefresh )


