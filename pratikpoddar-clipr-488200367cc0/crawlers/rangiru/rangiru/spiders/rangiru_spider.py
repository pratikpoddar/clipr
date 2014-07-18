from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from rangiru.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "rangiru"
	allowed_domains = ["rangiru.com"]
	start_urls = [
	"http://www.rangiru.com",
	"http://www.rangiru.com/category/bestsellers/",
	"http://www.rangiru.com/category/gifts-collectibles/",
	"http://www.rangiru.com/category/earth-friendly/",
	"http://www.rangiru.com/category/quirky/",
	"http://www.rangiru.com/category/apparel-footwear/",
	"http://www.rangiru.com/category/home-lifestyle/",
	"http://www.rangiru.com/category/handbags/",
	"http://www.rangiru.com/category/jewelry/",
	"http://www.rangiru.com/shop/",
	"http://www.rangiru.com/shop/?&page=2",
	"http://www.rangiru.com/shop/thedal/",
	"http://www.rangiru.com/shop/style-different-way/",
	"http://www.rangiru.com/shop/maharani-saree/",
	"http://www.rangiru.com/shop/avishacreations/",
	"http://www.rangiru.com/shop/keeda-factory/",
	"http://www.rangiru.com/shop/aliff-lailaa/",
	]
	rules = (
		Rule(SgmlLinkExtractor(deny=('/login','/accounts','/search')), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//div[@id="product_images"]/text()')
	if len(tester) > 0:
		
		#link
		item['link'] = response.url
		
		#title
		item['title'] = site.select('//span[@itemprop="name"]/text()').extract()[0]
		
		#markprice and price
		item['price'] = trimPrice(site.select('//div[@itemprop="price"]//h2/text()').extract()[0])
		markprice = site.select('//div[@itemprop="price"]//h3/del/text()').extract()
		if len(markprice) > 0:
			item['markprice'] = trimPrice(markprice[0])
		else:
			item['markprice'] = item['price']
		
		#description
		item['description'] = site.select('//div[@itemprop="description"]').extract()[0]
		item['delivery'] = site.select('//div[@class="alert alert-success"]//center/text()').extract()[0]
		item['availability']=site.select('//span[@itemprop="availability"]/@content').extract()[0].replace('_',' ')
		#images
		images = site.select('//div[@class="row"]/ul[@id="thumbnails"]/li[@class="thumbnail"]/a/@href').extract()
		if len(images) > 0:
			images = map( lambda x: 'http://www.rangiru.com'+ x, images )
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

		#recommendations dont exist for this site
		recommendations = site.select('//div[@id="more_like_this_product"]/ul/li/div/a/@href').extract()
		if len(recommendations) > 0:
			item['recid'] = reduce((lambda x, y: x+ '$$$' + y), recommendations )
		else:
			item['recid'] = ''
		
		#TODO: Finalize whether link is ok for buylink
		#buylink: depends on what size is chosen
		item['buylink'] = ""

		#categorizations
		categories =site.select('//ul[@class="breadcrumb"]/li/a/text()').extract()
		if len(categories) > 1:
			item['category'] = reduce((lambda x, y: x+ '$$$' + y), categories[1:] )
		else:
			item['category'] = ''
		item['demographics'] = ""
		items.append(item)
		print item	
	return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())
