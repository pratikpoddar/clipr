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
from afday.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "afday"
	allowed_domains = ["afday.com"]
	start_urls = [
	"http://www.afday.com/",
	"http://www.afday.com/collections/paper-craft",
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
	tester = site.select('//div[@id="product"]/text()').extract()
	if len(tester) > 0:
		url = response.url
		tokens = url.split('/')
		currId = tokens[len(tokens)-1]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'afday'
			#title
			item['title'] = site.select('//h1[@id="product-title"]/text()').extract()[0]
			
			#markprice and price
			jspage = getJsLoadedPage(response.url)
			price = jspage.select('//div[@id="purchase"]//strong/span[@class="money"]/text()').extract()
			markprice = jspage.select('//div[@id="purchase"]//span[@class="compare_at_price"]//span[@class="money"]/text()').extract()
			if price:
				item['availability'] = "in stock"
				item['price']=trimPrice(price[0])
				if markprice:
					item['markprice'] = trimPrice(markprice[0])
				else:
					item['markprice'] = item['price']
			elif site.select('//div[@id="product-actions"]/@class').extract()[0] == "sold-out":
				item['price'] = trimPrice(site.select('//span[@itemprop="price"]/span[@class="money"]/text()').extract()[0])
				item['markprice'] = item['price']
				item['availability'] = "out of stock"
			item['delivery']=""
			item['shippingcost']=""

			#description
			description = site.select('//div[@class="description"]').extract()[0]
			item['description'] = description.split('<ul id="sharing">')[0]

			#images
			images = site.select('//ul[@id="thumbs"]/li/a/@href').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			recommendations = site.select('//section[@class="diagonal-divider clearfix"]/ul/li/a/@href').extract()
			recommendations = map(lambda x: 'http://www.afday.com'+x, recommendations)
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
			print item
	return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())

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
