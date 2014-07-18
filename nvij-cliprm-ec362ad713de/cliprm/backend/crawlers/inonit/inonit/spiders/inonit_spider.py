import sys
import os
currDir = os.path.dirname(os.path.abspath(__file__))
greatGrandParentDir = reduce( lambda x,y : x+'/'+y, currDir.split('/')[:-3])
sys.path.insert(0, greatGrandParentDir)
from pyvirtualdisplay import Display
from selenium.webdriver.firefox.firefox_profile import FirefoxProfile
from selenium.webdriver.support.ui import WebDriverWait
from selenium import webdriver
from contextlib import closing
from selenium.webdriver import Firefox

from scrapy.http import Request, TextResponse, Response
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from inonit.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "inonit"
	allowed_domains = ["shop.inonit.in","inonit.in"]
	start_urls = [
	"http://www.inonit.in/",
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


def parser(link):
	sp = BaseSpider('some')
	display = Display(visible=0, size=(800, 600))
	display.start()
	with closing(Firefox()) as browser:
		
		browser.get(link)
		
		respText = browser.page_source
		sreq = sp.make_requests_from_url(link)
		response = TextResponse(url=link,status=200,body=respText,encoding='utf-8')
		
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		tester = site.select('//div[@itemprop="name"]/h1/text()').extract()
		if len(tester) > 0:
			currId = response.url.split('#')[0].split('/')[-1].split('pid-')[1].split('.')[0]
			if currId not in visitedIds:
				visitedIds.add(currId)
				item['uniqueId']=currId
				#link
				item['link'] = response.url
				
				item['siteId'] = 'inonit'
				#title
				item['title'] = site.select('//div[@itemprop="name"]/h1/text()').extract()[0]
				
				
				#markprice and price
				price = site.select('//span[@itemprop="price"]/text()').extract()[0]

				#commenting out selenium browser code in favour of hacky size specefic code
				price = browser.execute_script("return PriceControl.Default_MRP;")
				# res = browser.find_element_by_class_name('currency')[1]
				# res.click()
				# page_source = browser.page_source
				# WebDriverWait(browser, timeout=20).until( lambda x: x.find_element_by_id('visible-image-large'))
				# price = browser.find_elements_by_xpath('//span[@itemprop="price"]/text()')

				item['price']=trimPrice(price)
				item['markprice'] = trimPrice(price)


				item['availability'] = "Available"
				item['delivery'] = ""
				item['shippingcost']=""

				#description
				item['description'] = site.select('//div[@id="Description"]').extract()[0]

				#images
				images = site.select('//div[@class="mutipleimgs_thumbs"]//div[@class="imgthumbnail"]/a/img/@src').extract()
				images = map(lambda x: x.split(';')[0]+';width=450', images)
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

				#recommendations dont exist for this site
				item['recid'] = ""
				
				#TODO: Finalize whether link is ok for buylink
				#buylink: depends on what size is chosen
				item['buylink'] = item['link']

				#categorizations
				categoryList = site.select('//div[@class="breadcrumlnk"]/a/text()').extract()
				if len(categoryList)>2:
					item['category'] = reduce( lambda x,y:x+'$$$'+y, categoryList[2:] )
				else:
					item['category'] =""
				item['demographics'] = ""
				items.append(item)
				print item
	display.stop()
	return items

def trimPrice(price):
	price = str(price)
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()
