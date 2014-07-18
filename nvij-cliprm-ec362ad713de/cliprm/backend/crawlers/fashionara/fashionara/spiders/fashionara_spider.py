import string
import re
import urllib2
import json
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from fashionara.items import CliprItem

###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "fashionara"
	allowed_domains = ["fashionara.com"]
	start_urls = [
	"http://www.fashionara.com/ladies.html?dir=desc&order=created_at",
	"http://www.fashionara.com/men.html",
	"http://www.fashionara.com/kids.html",
	"http://www.fashionara.com/footwear.html",
	"http://www.fashionara.com/lifestyle.html",
	]
	rules = (
		Rule(SgmlLinkExtractor(), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)

def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//div[@class="product-essential"]/form//input[@name="product"]/@value').extract()
	if len(tester) > 0:
		currId = tester[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url

			item['siteId'] = 'fashionara'
			#title
			item['title'] = site.select('//div[@class="product-name"]/h1/text()').extract()[0]
			
			#markprice and price
			oldprice = site.select('//div[@class="price-button"]/div[@class="price-box"]/span[@class="old-price"]/span[@class="price"]/text()').extract()
			regularprice = site.select('//div[@class="price-button"]/div[@class="price-box"]/span[@class="regular-price"]/span[@class="price"]/text()').extract()
			specialprice = site.select('//div[@class="price-button"]/div[@class="price-box"]/span[@class="special-price"]/span[@class="price"]/text()').extract()

			if len(oldprice):
				item['markprice'] = trimPrice(oldprice[len(oldprice)-1])
				item['price'] = trimPrice(specialprice[0])
			else:
				item['markprice'] = trimPrice(regularprice[0])
				item['price'] = trimPrice(regularprice[0])

			#description
			item['description'] = site.select('//div[@id="product_tabs_one_contents"]/div[@class="std"]').extract()[0]
			item['delivery'] = getDeliveryTime()
			addToCartButton = site.select('//button[@id="add_to_cart_button"]').extract()
			if addToCartButton:
				item['availability'] = "in stock"
			else:
				item['availability'] = "out of stock"
			#images
			images = site.select('//ul[@id="thumbs-slide"]/li/a/@href').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			recommendations = site.select('//div[@id="bestselling"]/ul/li//a/@href').extract()
			item['recid'] = ""
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			breadcrumbs = site.select('//div[@class="breadcrumbs"]/ul/li/a/text()').extract()
			if len(breadcrumbs)>1:
				breadcrumbs = breadcrumbs[1:]
				item['category'] = (reduce(lambda x,y:x+'$$$'+y, breadcrumbs))
			else:
				item['category'] =""
			items.append(item)
	return items

def getDeliveryTime():
	req = urllib2.Request("http://www.fashionara.com/fashionara/?postcode=400072")
	opener = urllib2.build_opener()
	data = json.loads(str(opener.open(req).read()),"utf-8")			
	return json.loads(data['day'],"utf-8")['Standard_Shipment']

def trimPrice(price):
	price =filter(lambda x: x in string.printable, price)
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.split('.')[0])

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()