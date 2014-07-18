import time
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from villcart.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "villcart"
	allowed_domains = ["villcart.com"]
	start_urls = [
	"http://www.villcart.com/show-pieces.html",
	"http://www.villcart.com/furnishing.html",
	"http://www.villcart.com/kitchen.html",
	"http://www.villcart.com/spiritual.html",
	"http://www.villcart.com/toys.html",
	"http://www.villcart.com/jewellery.html",
	"http://www.villcart.com/accessories.html",
	"http://www.villcart.com/stationery.html",
	"http://www.villcart.com/other.html",
	"http://www.villcart.com/organic-foods.html",
	"http://www.villcart.com//foods.html",
	"http://www.villcart.com/aromatherapy.html",
	"http://www.villcart.com/books.html",
	"http://www.villcart.com/magazines.html",
	"http://www.villcart.com/music.html",
	"http://www.villcart.com"
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(deny=('/modal','/askquestion/',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//div[@class="product-name"]/h1/text()').extract()
	if len(tester) > 0:
		urlTokens = response.url.split('/')
		prodId = urlTokens[len(urlTokens) - 1]
		if prodId not in visitedIds:

			visitedIds.add(prodId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'villcart'			
			#title
			item['title'] = site.select('//div[@class="product-name"]/h1/text()').extract()[0]
			
			#markprice and price
			price = site.select('//div[@class="price-box"]/span[@class="regular-price"]/span[@class="price"]/text()').extract()[0]
			price = trimPrice(price)

			item['price'] = price

			markprice = site.select('//div[@class="PricediscountAmount"]/span[@class="PricediscountAmount"]/text()').extract()

			if markprice and trimPrice(markprice[0]):
				item['markprice'] = trimPrice(markprice[0])
			else:
				item['markprice'] = price
			
			#description
			item['description'] = site.select('//div[@class="product-tabs-content"]/div[@class="std"]').extract()[0]
			item['availability'] = site.select('//p[contains(@class,"availability ")]//span/text()').extract()[0].lower()
			item['delivery'] = "Expected delivery within 8-10 working days"
			item['shippingcost'] = "Free for orders of more than Rs 200"
			#images
			images = site.select('//div[@class="more-views"]//ul/li//a[@class="cloud-zoom-gallery"]/@href').extract()

			if len(images) > 0:
				item['image'] =  reduce( (lambda x,y: x+'$$$'+y), images) 
			else:
				item['image'] = ''

			#recommendations dont exist for this site
			item['recid'] = ""
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			breadcrumbs = site.select('//div[@class="breadcrumbs"]/ul/li/a/text()').extract()

			item['category'] = 'crafts'
			if len(breadcrumbs)>=2:
				item['category'] = reduce( (lambda x,y: x+'$$$'+y), breadcrumbs[1:])

			time.sleep(2)
			items.append(item)

	return items

def trimPrice(pr):
	price = str(pr)
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())
