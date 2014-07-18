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
from myntra.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "myntra"
	allowed_domains = ["myntra.com"]
	start_urls = [
	"http://www.myntra.com/",
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
	tester = site.select('//h1[@itemprop="name"]/text()').extract()
	if len(tester) > 0:
		url = response.url
		currId = site.select('//input[@id="productStyleId"]/@value').extract()[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'myntra'
			#title
			item['title'] = site.select('//h1[@itemprop="name"]/text()').extract()[0]
			#markprice and price
			item['price'] = trimPrice(site.select('//meta[@itemprop="price"]/@content').extract()[0])
			item['markprice'] = trimPrice(site.select('//input[@name="unitPrice"]/@value').extract()[0])

			item['availability'] = "in stock"
			item['delivery']=""
			item['shippingcost']="FREE SHIPPING ON ORDERS OVER RS. 799"

			#description
			description = site.select('//li[@itemprop="description"]').extract()[0]
			item['description'] = description

			#images
			images = site.select('//ul[@class="rs-carousel-runner"]/li/a/@href').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			item['recid'] = ""
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//div[@itemprop="breadcrumb"]/a/text()').extract()
			if len(categoryList)>1:
				item['category'] = (reduce(lambda x,y:x+'$$$'+y, categoryList))
			else:
				item['category'] =""
			item['demographics'] = ""
			items.append(item)
			print item
	return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()
