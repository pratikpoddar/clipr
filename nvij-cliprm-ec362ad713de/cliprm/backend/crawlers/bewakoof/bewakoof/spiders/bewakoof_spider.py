import string
import re
from bs4 import BeautifulSoup
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from bewakoof.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "bewakoof"
	allowed_domains = ["bewakoof.com"]
	start_urls = [	"http://www.bewakoof.com/",]
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
	tester = response.url.split('/product/')
	if len(tester) > 1:
		currId = ((response.url.split('/product/')[1]).split('/')[0]).split('?')[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'bewakoof'
			#title
			item['title'] = site.select('//h1/text()').extract()[0]
			
			#markprice and price
			oldprice = site.select('//h1/text()').extract()
			newprice = site.select('//h1/text()').extract()

			if len(oldprice) > 1:
				item['markprice'] = trimPrice(oldprice[1])
				item['price'] = trimPrice(newprice[1])

			#description
			item['availability'] = "in stock"

			desc = site.select('//div[@class="panel"]').extract()
			if desc:
				item['description'] = desc[0]
			else:
				item['description'] = item['title']

			#images
			images = site.select('//div[@id="content_product_middle_left"]//a/@href').extract()
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			item['recid'] = reduce(lambda x, y: x+'$$$'+y, map(lambda x: "http://www.bewakoof.com"+x, site.select('//div[@id="content_product_related_box"]/a/@href').extract()))
			item['buylink'] = item['link']
			item['category'] =""
			item['delivery']=""
			items.append(item)
			print item
	return items

def trimPrice(price):
	price = price.replace('Rs.','').replace('Rs','')
	non_decimal = re.compile(r'[^\d.]+')
	val = non_decimal.sub('', str(price))
	return int(val.split('.')[0])

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()

def getAvailability(txt):
	return re.sub("\s+"," ",reduce(lambda x,y: x+y,txt)).strip().lower()
