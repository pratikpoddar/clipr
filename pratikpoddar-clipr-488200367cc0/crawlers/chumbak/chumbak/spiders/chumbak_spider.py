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
from chumbak.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "chumbak"
	allowed_domains = ["chumbak.com"]
	start_urls = [
	"http://www.chumbak.com/",
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(deny=()), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem() 
	tester = site.select('//div[@class="product-name"]/h1/text()').extract()
	url = response.url
	if len(tester) > 0:
		currId = site.select('//input[@name="product"]/@value').extract()[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'chumbak'
			#title
			item['title'] = site.select('//div[@class="product-name"]/h1/text()').extract()[0]
			
			item['availability'] = site.select('//p[contains(@class,"availability")]/span/text()').extract()[0]
			specialprice = site.select('//p[@class="special-price"]/span[@class="price"]/text()').extract()
			regularprice = site.select('//span[@class="regular-price"]/span[@class="price"]/text()').extract()
			oldprice = site.select('//p[@class="old-price"]/span[@class="price"]/text()').extract()
			if specialprice:
				item['price']=trimPrice(specialprice[0])
				item['markprice']=trimPrice(oldprice[0])
			else:
				item['price']=trimPrice(regularprice[0])
				item['markprice']=trimPrice(regularprice[0])

			item['delivery']="2-3 days in metros, 7-10 days in other cities"
			item['shippingcost']="Rs. 60 for orders under Rs. 300. Free for bigger orders. "

			#description
			item['description']  = site.select('//div[@class="std"]').extract()[0]

			#images
			image1 = site.select('//img[@id="image"]/@src').extract()[0]
			images = site.select('//div[@class="more-views"]/ul/li/a/img/@src').extract()
			images = map(lambda x: x.replace('/thumbnail/56x/','/image/'), images)
			images.insert(0,image1)
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )


			#recommendations dont exist for this site
			recommendations = site.select('//od[@id="block-related"]/li/div[@class="product"]/a/@href').extract()
			item['recid'] = ""
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//div[@class="breadcrumbs"]/ul/li/a/@text()').extract()
			if len(categoryList)>1:
				item['category'] = reduce(lambda x,y: x+'$$$'+y, categoryList[1:])
			else:
				item['category'] =""
			item['demographics'] = ""
			items.append(item)
			print item
	return items

def trimPrice(pr):
	price = str(pr)
	return int(float(trim(price.replace("Rs.",'').replace("Rs",'').replace("/",'').replace(',','').strip())))

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()
