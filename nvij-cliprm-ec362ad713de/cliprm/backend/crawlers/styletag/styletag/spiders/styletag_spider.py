import string
import re
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from styletag.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "styletag"
	allowed_domains = ["styletag.com"]
	start_urls = [
	"http://styletag.com/t/sales/women",
	"http://styletag.com/t/sales/men",
	"http://styletag.com/t/sales/home-and-living",
	"http://styletag.com/t/sales/bags-extravaganza",
	"http://www.styletag.com/collections/soft-furnishings"
	"http://www.styletag.com/collections/lamps"
	"http://www.styletag.com/collections/knick-knacks",
	"http://www.styletag.com/collections/ceramics-pottery",
	"http://www.styletag.com/collections/gifts",
	"http://www.styletag.com/collections/paper-craft",
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
	tester = site.select('//h3[@class="heading"]/span/text()').extract()
	if len(tester) > 0:
		currId = int((site.select('//div[@id="radio2"]/input/@id').extract()[0]).split('_')[1])
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'styletag'
			#title
			item['title'] = site.select('//h3[@class="heading"]/span/text()').extract()[0]
			
			#markprice and price
			newprice = site.select('//div[@id="price-and-details"]//p[@class="prices"]/span[@class="price selling"]/text()').extract()
			oldprice = site.select('//div[@id="price-and-details"]//p[@class="prices"]/del/span/text()').extract()

			item['price'] = trimPrice(newprice[0])
			if len(oldprice):
				item['markprice'] = trimPrice(oldprice[0])
			else:
				item['markprice'] = trimPrice(newprice[0])
			
			#description
			item['description'] =  site.select('//div[@id="product-description"]').extract()[0]

			#images
			images = site.select('//div[@id="product-images"]//ul[@id="product-thumbnails"]/li/a/@href').extract()
			if not len(images):
				images = site.select('//div[@id="main-image"]/div[@id="image-medium"]/a/@href').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			recommendations = site.select('//div[@id="referral-products"]//a/@href').extract()
			recommendations = map(lambda x: 'http://www.styletag.com'+x, recommendations)
			item['recid'] = ""
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			
			item['buylink'] = item['link']

			#categorizations
			category = site.select('//h3[@class="heading"]/a/text()').extract()
			if len(category)>0:
				item['category'] = (reduce(lambda x,y:x+'$$$'+y, category))
			else:
				item['category'] = ""
			item['delivery'] = "Ships within 4 weeks of the end of sale"
			if site.select('//div[@class="oos"]').extract():
				item['availability'] = "out of stock"
			else:
				item['availability'] = "in stock"
			item['shippingcost']="free shipping for metro most cities"
			print item
			items.append(item)
	return items

def trimPrice(price):
	price =filter(lambda x: x in string.printable, price)
	price = price.replace('Rs.','')
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.split('.')[0])

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()