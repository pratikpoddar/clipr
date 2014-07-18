from bs4 import BeautifulSoup
import sys
import os
import string
import re
from scrapy.http import Request, TextResponse, Response
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from chemicallocha.items import CliprItem

###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "chemicallocha"
	allowed_domains = ["thechemicallocha.com"]
	start_urls = [
	"http://www.thechemicallocha.com/",
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
	tester = site.select('//h1[@class="title"]/text()').extract()
	if len(tester) > 0:
		currId = site.select('//input[@id="product_id"]/@value').extract()[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'chemicallocha'
			#title
			item['title'] = site.select('//h1[@class="title"]/text()').extract()[0]
			
			#markprice and price  item['price']
			markprice = site.select('//div[@id="mrp"]//*[contains(@class,"mrp")]').extract()
			if markprice:
				markprice = markprice[0]
				markpricesoup = BeautifulSoup( reduce( lambda x,y: x + ' ' + y, markprice ))
				
				item['price'] = trimPrice( markpricesoup.get_text() )
				item['markprice'] = trimPrice( markpricesoup.get_text() )
			else:
				item['markprice'] = trimPrice(site.select('//div[@id="mrp"]//span[@class="linecut"]/text()').extract()[0])
				item['price'] = trimPrice(site.select('//div[@id="discount"]//span[@class="offerprice"]/text()').extract()[0])

			#description
			description = site.select('//div[@id="product_detail"]').extract()[0]
			item['description'] = description

			item['delivery'] = ""
			item['availability'] = "in stock"
			item['shippingcost'] = site.select('//div[@id="shipcharge"]//span/text()').extract()[0]

			#images
			images = site.select('//div[@id ="thumbs"]//div[@class="detailthumbs"]/a/@href').extract()
			if len(images) > 0:
				images = map(lambda x: addPrefixIfRequired(x), images)
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )
			else:
				item['image'] = addPrefixIfRequired(site.select('//img[@id="largeImage"]/@src').extract()[0])

			#recommendations are loaded via ajax
			item['recid'] = ""

			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//div[@class="breadcrumbs"]//a/text()').extract()
			if len(categoryList)>0:
				item['category'] = (reduce(lambda x,y:x+'$$$'+y, categoryList))
			items.append(item)
	return items

def trimPrice(price):
	price =filter(lambda x: x in string.printable, price)
	price = price.replace('Rs.','')
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.lstrip('.').split('.')[0])

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()
def addPrefixIfRequired(img):
	if img.find("http://") < 0:
		return "http://www.thechemicallocha.com"+img
	else:
		return img
