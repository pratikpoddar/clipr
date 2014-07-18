from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from urbantouch.items import CliprItem

###Completed
visitedIds = set()

class DmozSpider(CrawlSpider):
	name = "urbantouch"
	allowed_domains = ["urbantouch.com"]
	start_urls = [
	"http://www.urbantouch.com/"
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
	tester = site.select('//h1[@itemprop="name"]/text()').extract()

	if len(tester) > 0:
		urlTokens = response.url.split('/p/')[0].split('/')
		currId = urlTokens[len(urlTokens) - 1 ]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'urbantouch'
			#title
			item['title'] = site.select('//h1[@itemprop="name"]/text()').extract()[0]
			
			#markprice and price  item['price']
			markprice = site.select('//div[@class="product-view-price"]/p[@class="old-price"]//span[@id="mrp"]/text()').extract()
			price = site.select('//div[@class="product-view-price"]//span[@class="oprice"]/text()').extract()
			
			item['price'] = trimPrice( price[0] )
			if markprice:
				item['markprice'] = trimPrice( markprice[0] )
			else:
				item['markprice'] = trimPrice( price[0] )

			#description
			description = site.select('//div[@itemprop="description"]').extract()[0]
			description2 = site.select('//div[@id="product_details"]/div[@class="std"]').extract()[0]
			item['description'] = description+"<description2>"+description2+"</description2>"

			item['delivery'] = "Delivers in 4-6 working days"
			if site.select('//button[@id="prod_id"]'):
				item['availability'] = "in stock"
			else:
				item['availability'] = "out of stock"
			item['shippingcost'] = "Free for all orders of Rs. 500 or more. Rs. 50 for other orders."

			#images
			images = site.select('//div[@class ="all-image"]/a/img[@itemprop="image"]/@src').extract()
			if len(images) > 0:
				images = map( lambda x: 'http:'+ x, images )
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			item['recid'] = ""
			recommendations = site.select('//div[@id="suggested_items_list"]//div[@class="single-item"]//a[@class="recommended-prod"]/@href').extract()
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//ul[@class="custom-breadcrumb"]/li/span/a/span/text()').extract()
			if len(categoryList)>0:
				item['category'] = (reduce(lambda x,y:x+'$$$'+y, categoryList))
			item['demographics'] = "women"
			items.append(item)
			print item	
	return items

def trimPrice(pr):
	price = str(pr)
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())
