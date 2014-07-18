import string
import re
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from itsourstudio.items import CliprItem
#Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "itsourstudio"
	allowed_domains = ["itsourstudio.com"]
	start_urls = [
		"https://itsourstudio.com/ProductListing.php?cat=Lifestyle"
		"https://itsourstudio.com/ProductListing.php?cat=Outdoor",
		"https://itsourstudio.com/ProductListing.php?cat=Party",
		"https://itsourstudio.com/ProductListing.php?cat=USBs",
		"https://itsourstudio.com/ProductListing.php?cat=Accessories",
		"https://itsourstudio.com/ProductListing.php?cat=Gadget",
		"https://itsourstudio.com/ProductListing.php?cat=Moulds",
		"https://itsourstudio.com/"
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	url = response.url
	tester = site.select('//p[@class="sku"]/text()').extract()
	if len(tester) > 0:
		currId = tester[0].replace('Product Code','').replace(':','').strip()
		if currId not in visitedIds:
			visitedIds.add(currId)
			url = response.url
			category = site.select('//div[@class="breadcrumbs"]/ul/li/a/text()').extract()
			category = category[1:]
			if category:
				item['category'] = reduce((lambda x,y: x+'$$$'+y), category)
			else:
				item['category']=''
			item['link'] = response.url
			item['title'] = site.select('//h1[@class="product-name"]/text()').extract()[0].strip()
			price = trimPrice(site.select('//div[@class="product-shop"]//span[@class="price"]/text()').extract()[0])
			item['markprice'] = price
			item['price'] = price
			desc = site.select('//div[@class="productDesctription"]').extract()
			desc2 = site.select('//meta[@property="og:description"]/@content').extract()
			if desc2:
				item['description'] = desc2[0]
			elif desc:
				item['description'] = desc[0]
			else:
				item['description'] = ""
			item['availability'] = site.select('//div[@class="aviability"]//ul/li[@class="stock"]/text()').extract()[0].strip()
			item['delivery']="Delivers in 3-4 working days in metros. 8-10 working days in other cities"
			item['shippingcost']="Standard rates and our shipping charges are based on volume of the particular product"
			images = site.select('//div[@class="more-view"]/ul/li/a/@href').extract()
			item['image'] =  reduce((lambda x,y: x+'$$$'+y), images)
			item['recid'] = ""#site.select('//img[@width="500"]/@src').extract()
			item['buylink'] = response.url#site.select('//img[@width="500"]/@src').extract()
			item['siteId'] = 'itsourstudio'
			print item
			items.append(item)
	return items

def trimPrice(price):
	price =filter(lambda x: x in string.printable, price)
	price = price.replace('Rs.','')
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.split('.')[0])
