import re
from pyvirtualdisplay import Display
from selenium.webdriver.firefox.firefox_profile import FirefoxProfile
from selenium.webdriver.support.ui import WebDriverWait
from selenium import webdriver
from contextlib import closing
from selenium.webdriver import Firefox
import urllib2
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from flipkart.items import CliprItem
from scrapy.exceptions import IgnoreRequest
from scrapy.http import Request


visitedIds = dict()
visitedUrls = set()

class DmozSpider(CrawlSpider):

	name = "flipkart"
	allowed_domains = ["flipkart.com"]
	start_urls = [
		"http://www.flipkart.com/books?_pop=mheader",
		"http://www.flipkart.com/phones?_pop=mheader",
		"http://www.flipkart.com/computers?_pop=mheader",
		"http://www.flipkart.com/cameras?_pop=mheader",
		"http://www.flipkart.com/pens-office-supplies/pens/parker?cid=20304&_pop=flyout"
	]

	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(allow=('/p/',)), callback='parse_item',follow=True, process_request = 'process_request'),
		Rule(SgmlLinkExtractor(),follow=True, process_request='process_request'),
	)


	def process_request(spider, request):
		url = request.url
		if url.find("/p/") >= 0:
			currId = -1
			if url.find('?pid=') >= 0:
				currId = (url.split('?pid=')[1].split('&'))[0]
			elif url.find('&id=') >= 0:
				currId = (url.split('&pid=')[1].split('&'))[0]
			if currId in visitedIds:
				return Request(url=visitedIds[currId]) 
		else:
			currLink = url
			if url.find('?') >= 0:
				currLink = (url.split('?'))[0]
			if currLink in visitedIds:
				return Request(url=visitedIds[currLink]) 
			else:
				visitedIds[currLink] = url
		return request

	def parse_item(self, response):
		return parser(response)

def getSize(img,sz):
	newsize = str(sz)+'x'+str(sz)
	if len(img.split('-')) > 2:
		oldsize = img.split('-')[-2]
		return img.replace(oldsize,newsize)

def isPresent(img,sz):
	try:
		urllib2.urlopen(getSize(img,sz)).getcode()
	except Exception, e:
		return False
	return True
def validSizeCheck(img):
	if len(img.split('-')) > 2:
		oldsize = img.split('-')[-2]
		if re.match('[0-9]+x[0-9]+', oldsize):
			return True
	return False

def parserSmallImage(response):
	return parser(response, True)

def parser(response, smallimage=False):
	url = response.url
	items = []
	if url.find("/p/") >= 0:
		currId = -1
		if url.find('?pid=') >= 0:
			currId = (url.split('?pid=')[1].split('&'))[0]
		elif url.find('&id=') >= 0:
			currId = (url.split('&pid=')[1].split('&'))[0]

		if currId not in visitedIds:
			visitedIds[currId] = url
			site = HtmlXPathSelector(response)
			item = CliprItem()

			item['uniqueId'] = currId
			#siteId
			item['siteId']=str('flipkart')

			#link
			item['link'] = url

			#title
			item['title'] = site.select('//h1/text()').extract()[0].strip()

			#price
			price = site.select('//meta[@itemprop="price"]/@content').extract()[0]
			markPrice = site.select('//span[@id="fk-mprod-list-id"]/text()').extract()
			item['price']= price
			if len(markPrice) > 0:
				item['markprice']= markPrice[0]
			else:
				item['markprice']= price


			item['markprice'] = trimPrice(item['markprice'])
			item['price'] = trimPrice(item['price'])

			#images

			largerimg = site.select('//a[@id="large-image-link"]').extract()
			images = site.select('//div[@class="multi-images"]//ul[@id="pp-small-carousel"]/li//img/@src').extract()
			imageMain =site.select('//div[@class="visible-image-small"]//div[@class="image-wrapper"]//img[@id="visible-image-small"]/@src').extract()
			smallDisplayImg =site.select('//div[@class="mprodimg"]/img/@data-src').extract()
			if len(images) > 0:
				images = map(lambda x: x.replace('40x40','400x400'), images)
				item['image'] = reduce(lambda x, y: x+'$$$'+y, images)
			elif len(imageMain) > 0:
				item['image'] = imageMain[0]
				images = [imageMain[0]]
			elif len(smallDisplayImg) > 0:
				item['image']=smallDisplayImg[0]
				images = [smallDisplayImg[0]]
			else:
				item['image']=''
			if validSizeCheck(images[0]):
				if isPresent(images[0],700):
					newimages = map (lambda x: getSize(x,700), images)
				elif isPresent(images[0],400):
					newimages = map (lambda x: getSize(x,400), images)
				elif isPresent(images[0],275):
					newimages = map (lambda x: getSize(x,275), images)
				else:
					newimages = images
				item['image'] = reduce(lambda x, y: x+'$$$'+y, newimages)
			elif (not smallimage) and item['image'] and site.select('//a[@id="large-image-link"]').extract():
				#commenting out selenium browser code in favour of hacky size specefic code
				display = Display(visible=0, size=(800, 600))
				display.start()
				with closing(Firefox()) as browser:
					browser.get(url)
					res = browser.find_element_by_id('large-image-link')
					res.click()
					page_source = browser.page_source
					WebDriverWait(browser, timeout=10).until( lambda x: x.find_element_by_id('visible-image-large'))
					thumbnails = browser.find_elements_by_xpath('//ul[@id="pp-large-carousel"]/li/div')
					if thumbnails:
						imglist = []
						for thumbnail in thumbnails:
							thumbnail.click()
							imglist.append(browser.find_element_by_xpath("//img[@id='visible-image-large']").get_attribute("src"))
						item['image'] = reduce(lambda x, y: x+'$$$'+y, imglist)
					else:
						item['image'] = browser.find_element_by_xpath("//img[@id='visible-image-large']").get_attribute("src")
				display.stop()

			#description
			availability = site.select('//div[@id="fk-stock-info-id"]/text()').extract()
			if availability:
				item['availability'] = availability[0]
				if item['availability'].lower().replace('.','').strip() == "out of stock" :
					item['delivery'] = ""
			if item.get('delivery') != "":
				if site.select('//div[@class="shipping-details"]/text()').extract():
					item['delivery'] = getDeliveryTime(site.select('//div[@class="shipping-details"]/text()').extract())
				elif site.select('//span[@class="shipping-details"]/text()').extract():
					item['delivery'] = getDeliveryTime(site.select('//span[@class="shipping-details"]/text()').extract())
				else:
					item['delivery']=""

			desc = site.select('//div[@id="description"]').extract()
			desc2 = site.select('//div[@id="details"]').extract()
			if len(desc) > 0:
				item['description'] = trim(desc[0])
			elif len(desc2) > 0:
				item['description'] = trim(desc2[0])
			else:
				item['description'] = item['title']
			#buyLink
			buylink = site.select('//div[@id="mprod-buy-btn"]/a/@href').extract()
			if len(buylink) == 0:
				item['buylink'] = "Currently Unavailable"
			else:
				item['buylink'] = "http://www.flipkart.com"+buylink[0]

			#recommendations
			rec = site.select('//div[@id="same_vertical_recomm"]')
			if len(rec) > 0:
				recommendations = rec[0].select('.//a/@href').extract()
				linkRec = map((lambda x: "http://www.flipkart.com"+x), recommendations)
				item['recid'] = reduce(lambda x, y: x+'$$$'+y, linkRec)
			else:
				item['recid'] = ''
			
			#categories
			category=site.select('//div[@class="line fk-lbreadbcrumb"]/span/a/text()').extract()
			categoryNested=site.select('//div[@class="line fk-lbreadbcrumb"]/span/a/span/text()').extract()
			if len(category) > 1:
				categories = map((lambda x: x.lower()),category[1:])
				item['category'] = reduce(lambda x, y: x+'$$$'+y, categories)
			elif len(categoryNested) > 1:
				categories = map((lambda x: x.lower()),categoryNested[1:])
				item['category'] = reduce(lambda x, y: x+'$$$'+y, categories)
			else:
				item['category'] = ["misc"]
			items.append(item)
	return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(',','').replace('.','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').replace('&amp;','&').replace('&nbsp;',' ').strip()
def stripUrl(url):
	return url.split('?')[0]
def getDeliveryTime(txt):
	return re.sub("\s+"," ",reduce(lambda x,y: x+y,txt)).strip().lower()
	
