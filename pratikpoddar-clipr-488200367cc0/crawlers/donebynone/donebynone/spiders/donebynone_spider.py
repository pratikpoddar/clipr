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
from donebynone.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "donebynone"
	allowed_domains = ["donebynone.com"]
	start_urls = [
	"http://www.donebynone.com/",
	"http://www.donebynone.com/collections/paper-craft",
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
	tester = site.select('//span[@id="skudiv"]/text()').extract()
	if len(tester) > 0:
		url = response.url
		tokens = url.split('/')
		currId = tokens[len(tokens)-1]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'donebynone'
			#title
			item['title'] = site.select('//div[@class="prd_name"]/text()').extract()[0]
			
			item['availability'] = "in stock"
			#markprice and price
			old_price = site.select('//span[@class="old-price"]//span[@class="price"]/text()').extract()
			disc_price = site.select('//span[@class="special-price"]//span[@class="price"]/text()').extract()
			regular_price = site.select('//span[@class="regular-price"]/text()').extract()
			if disc_price:
				print disc_price
				item['price']=trimPrice(disc_price[0])
				item['markprice'] = trimPrice(old_price[0])
			else:
				item['price']=trimPrice(regular_price[0])
				item['markprice']=trimPrice(regular_price[0])

			item['delivery']="4-5 working day"
			item['shippingcost']="Free shipping"

			#description
			item['description'] = site.select('//div[@class="product-description"]').extract()[0]

			#images
			images = site.select('//div[@class="thumbnails"]/img/@rel').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			item['recid'] = ""

			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//div[@class="menu_bottom_links_cat"]/a/text()').extract()[1:]
			if categoryList:
				item['category'] = reduce(lambda x,y: x+'$$$'+y,categoryList)
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
