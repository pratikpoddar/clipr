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
from postergully.items import CliprItem
import string
import re
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "postergully"
	allowed_domains = ["postergully.com"]
	start_urls = [
	"http://www.postergully.com/",
	"http://www.postergully.com/collections/paper-craft",
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(deny=('^[^/]*//[^/]*/products/[^/]*$',
			'/collections/gifts-upto-rs-200',
			'/collections/rs-200-rs-500',
			'/collections/rs-500-rs-1500',
			'/collections/rs-1500-rs-3000',
			'/collections/above-rs-3000',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	url = response.url
	if url.find("/products/") >= 0:
		currId = site.select('//select[@id="product-select"]/option/@value').extract()[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'postergully'
			#title
			item['title'] = site.select('//h1[@id="product-title"]/text()').extract()[0]
			
			jspage = getJsLoadedPage(response.url)
			#markprice and price
			price = jspage.select('//div[@id="purchase"]/p[@class="price"]/strong/text()').extract()
			markprice = jspage.select('//div[@id="purchase"]//span[@class="compare_at_price"]/del/text()').extract()
			item['price']=trimPrice(price[0])
			item['markprice'] = trimPrice(markprice[0])

			item['availability'] = "in stock"
			item['delivery']="dispatched within 2-3 working days"
			item['shippingcost']="Free shipping for online payment. Additional 19 Rs for cash on delivery"

			#description
			description = site.select('//div[@class="description"]').extract()
			if description:
				item['description'] = description[0]
			else:
				item['description'] = "Featured poster by posterGully"

			#images
			images = site.select('//div[@class="MagicToolboxSelectorsContainer"]//a/@href').extract()
			if images:
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )
			else:
				item['image'] = site.select('//div[@class="MagicToolboxContainer"]//a/@href').extract()[0]

			#recommendations dont exist for this site
			recommendations = site.select('//section[@class="diagonal-divider clearfix"]/ul/li/a/@href').extract()
			recommendations = map(lambda x: 'http://www.postergully.com'+x, recommendations)
			item['recid'] = ""
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			category = site.select('//div[@class="description"]//tr//td//a/text()').extract()
			if category:
				item['category'] = category[0]
			else:
				item['category'] =""
			item['demographics'] = ""
			items.append(item)
			print item
	return items

def trimPrice(price):
	price =filter(lambda x: x in string.printable, price)
	price = price.replace('Rs.','')
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.lstrip('.').split('.')[0])

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()

def getJsLoadedPage(url):
	display = Display(visible=False, size=(1024, 768), color_depth=24)
	display.start()
	b = Browser()
	b.open(url)
	content = b.main_frame['content'].read()
	b.close()
	display.stop()
	jsresponse = TextResponse(url=url,status=200,body=content,encoding='utf-8')
	return HtmlXPathSelector(jsresponse)
