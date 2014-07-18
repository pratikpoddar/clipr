import sys
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from shaze.items import CliprItem

visitedIds = set()

def isProduct(url):
	return url.find('/p/') >0

def getId(url):
	return int((url.split('/p/')[1]).split('/')[0])

class DmozSpider(CrawlSpider):
	name = "shaze"
	allowed_domains = ["shaze.in"]
	start_urls = [
		"http://www.shaze.in"
	]
	rules = (
		# Extract links matching '/p/' for products and '/c/' for categories.
		Rule(SgmlLinkExtractor(allow=('/p/','/c/')), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	link = response.url
	if (not isProduct(link)):
		return []
	else:
		prodId = getId(link)
		items = []
		if prodId not in visitedIds:
			visitedIds.add(prodId)
			site = HtmlXPathSelector(response)
			item = CliprItem()

			item['link'] = response.url
			item['siteId'] = 'shaze'
			category = site.select('//div[@class="breadcrumb"]/a/text()').extract()
			category = filter(lambda x:x!="", category)
			category = filter(lambda x:x.find("You are in Shop")<0, category)
			
			if len(category) <= 1:
				print "Error: breadcrumb not found"
				sys.exit()
			item['title'] = category[len(category) - 1 ]
			item['category'] = category[:len(category)-1]

			item['price'] = trimPrice(site.select('//span[@class="productPrice"]/text()').extract()[0])
			item['markprice'] = item['price']

			item['image'] = site.select('//div[@class="picture"]/img/@src').extract()[0]
			item['description'] = site.select('//div[@class="overview"]/h2[@class="productnameOverview"]/text()').extract()[0]
			item['buylink'] = item['link']
			item['recid'] = ""
			items.append(item)

		return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())
