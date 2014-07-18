import sys
import os
currDir = os.path.dirname(os.path.abspath(__file__))
greatGrandParentDir = reduce( lambda x,y : x+'/'+y, currDir.split('/')[:-3])
sys.path.insert(0, greatGrandParentDir)
from webkit_browser import Browser
from pyvirtualdisplay import Display

from scrapy.http import Request, TextResponse, Response
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule

from gloob.items import CliprItem

import time

###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "gloob"
	allowed_domains = ["gloob.in"]
	start_urls = [
		"http://www.gloob.in/",
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(),callback='parse_item',follow=True),)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//form[@id="product_addtocart_form"]/@action').extract()
	if tester:
		buylink = tester[0]
		tokens = buylink.split('/')
		currId = tokens[len(tokens)-2]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'gloob'
			#title
			item['title'] = site.select('//div[@class="productInfo"]//h1/text()').extract()[0]
			
			#markprice and price
			pricecontainer = site.select('//div[@class="product-options"]//div[@id="newone"]/text()').extract()
			if not pricecontainer:
				display = Display(visible=False, size=(1024, 768), color_depth=24)
				display.start()
				b = Browser()
				b.open(response.url)
				content = b.main_frame['content'].read()
				b.close()
				display.stop()
				jsresponse = TextResponse(url=response.url,status=200,body=content,encoding='utf-8')
				jspage =  HtmlXPathSelector(jsresponse)
				price = jspage.select('//div[@class="product-options"]//div[@id="newone"]/text()').extract()[0]
			else:
				price = trimPrice(pricecontainer[0])

			item['markprice'] = price
			item['price'] = price

			#description
			item['description'] = site.select('//div[@class="desc"]').extract()[0]

			#images
			images = site.select('//div[@class="product-img-box"]//ul/li/a/@href').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			recommendations = site.select('//ol[@id="block-related"]/li/div/a/@href').extract()
			item['recid'] = ""
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			
			item['buylink'] = item['link']

			#categorizations
			item['category'] =""
			item['demographics'] = ""
			items.append(item)
	
	return items

def trimPrice(price):
	price =filter(lambda x: x in string.printable, price)
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.split('.')[0])

