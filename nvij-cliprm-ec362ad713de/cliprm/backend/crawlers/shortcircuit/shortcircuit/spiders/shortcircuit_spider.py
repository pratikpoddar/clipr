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
from shortcircuit.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "shortcircuit"
	allowed_domains = ["shortcircuit.in"]
	start_urls = [
	"http://www.shortcircuit.in/",
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
	tester = site.select('//div[@id="title"]/h1/text()').extract()
	if len(tester) > 0:
		currId = site.select('//input[@id="variant_id"]/@value').extract()[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'shortcircuit'
			#title
			item['title'] = site.select('//div[@id="title"]/h1/text()').extract()[0]
			
			#markprice and price
			markprice = site.select('//div[contains(@class,"list_price")]//span[@class="m-w"]/text()').extract()
			price = site.select('//div[contains(@class,"list_price")]//span[@class="m-w"]/text()').extract()

			item['price']=trimPrice(price[0])
			item['markprice'] = trimPrice(markprice[0])

			item['availability'] = site.select('//div[@id="in_stock"]//b/text()').extract()[0]
			item['delivery'] = site.select('//span[@class="ships-in"]//b/text()').extract()[0]
			item['shippingcost']=""

			#description
			item['description'] = site.select('//div[@id="description"]').extract()[0]

			#images
			images = site.select('//ul[@class="thumbnails"]/li/a/@data-zoom-url').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			recommendations = site.select('//div[@id="xsell"]//ul/li//a/@href').extract()
			recommendations = map(lambda x: 'http://shop.shortcircuit.in'+x, recommendations)
			item['recid'] = ""
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = response.url.split('/')
			if len(categoryList)>5:
				item['category'] = categoryList[4]
			else:
				item['category'] =""
			item['demographics'] = ""
			items.append(item)
	return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()
