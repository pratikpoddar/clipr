import sys
import os
import string
import re
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
from bluegape.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "bluegape"
	allowed_domains = ["bluegape.com"]
	start_urls = [
	"http://www.bluegape.com/",
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
	tester = site.select('//div[@class="product-name"]/h1/text()').extract()
	if len(tester) > 0:
		currId = site.select('//form[@id="product_addtocart_form"]//input[@name="product"]/@value').extract()[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'bluegape'
			#title
			item['title'] = site.select('//div[@class="product-name"]/h1/text()').extract()[0]
			
			#markprice and price  item['price']
			regularprice = site.select('//div[@class="price-box"]/span[@class="regular-price"]/span[@class="price"]/text()').extract()
			oldprice = site.select('//div[@class="price-box"]/p[@class="old-price"]/span[@class="price"]/text()').extract()
			specialprice = site.select('//div[@class="price-box"]/p[@class="special-price"]/span[@class="price"]/text()').extract()
			if regularprice:
				item['price'] = trimPrice( regularprice[0] )
				item['markprice'] = trimPrice( regularprice[0] )
			else:
				item['price'] = trimPrice( specialprice[0] )
				item['markprice'] = trimPrice( oldprice[0] )

			#description
			description = site.select('//div[@class="short-description"]//div[@class="std"]').extract()[0]
			item['description'] = description

			item['delivery'] = "Order usually ships in 1-4 business days. It takes another 2-3 business days for the order to reach to you."
			item['availability'] = site.select('//p[contains(@class,"availability ")]/span/text()').extract()[0]
			item['shippingcost'] = "Shipping is absolutely free, except a charge of INR 50 is levied if you choose to pay by Cash on Delivery"

			#images
			images = site.select('//ul[contains(@class,"thumbnails")]//a/@href').extract()
			if len(images) > 0:
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )
			else:
				item['image'] = site.select('//a[@id="zoom-btn"]/@href').extract()[0]

			#recommendations are loaded via ajax
			item['recid'] = ""
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//div[contains(@class,"breadcrumbs")]').extract()
			if len(categoryList)>1:
				item['category'] = (reduce(lambda x,y:x+'$$$'+y, categoryList[1:]))
			else:
				item['category']=""
			items.append(item)
	return items

def trimPrice(price):
	print price
	price =filter(lambda x: x in string.printable, price)
	price = price.replace('Rs.','')
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.lstrip('.').split('.')[0])

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()