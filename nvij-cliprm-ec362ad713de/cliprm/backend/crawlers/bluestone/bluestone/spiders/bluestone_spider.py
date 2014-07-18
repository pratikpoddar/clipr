from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from bluestone.items import CliprItem

#Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "bluestone"
	allowed_domains = ["bluestone.com"]
	start_urls = [
		"http://www.bluestone.com/earrings.html",
		"http://www.bluestone.com/pendants.html",
		"http://www.bluestone.com/rings.html",
		"http://www.bluestone.com/jewellery/rings-men.html",
		"http://www.bluestone.com/rings/the-divine-union-ring~839.html",
		"http://www.bluestone.com/jewellery/rings-men.html",
		"http://www.bluestone.com/jewellery/engagement-rings.html",
		"http://www.bluestone.com/jewellery/engagement-rings.html?id_category=2&p=2",
		"http://www.bluestone.com/jewellery/engagement-rings.html?id_category=2&p=3",
		"http://www.bluestone.com/jewellery/engagement-rings.html?id_category=2&p=4",
		"http://www.bluestone.com/jewellery/three+stone-rings.html",
		"http://www.bluestone.com/jewellery/single+stone-rings.html",
		"http://www.bluestone.com/jewellery/cluster-rings.html",
		"http://www.bluestone.com/jewellery/band-rings.html",
		"http://www.bluestone.com/jewellery/band-rings.html?id_category=2&p=2",
		"http://www.bluestone.com/jewellery/bangles.html",
		"http://www.bluestone.com/jewellery/bangles.html?id_category=2&p=2",
		"http://www.bluestone.com/jewellery/bangles.html?id_category=2&p=3",
		"http://www.bluestone.com/jewellery/below+rs+10000.html",
		"http://www.bluestone.com/jewellery/below+rs+10000.html?id_category=2&p=2",
		"http://www.bluestone.com/jewellery/below+rs+10000.html?id_category=2&p=3",
		"http://www.bluestone.com/jewellery/below+rs+10000.html?id_category=2&p=4",
		"http://www.bluestone.com/jewellery/below+rs+10000.html?id_category=2&p=5",
		"http://www.bluestone.com/jewellery/below+rs+10000.html?id_category=2&p=6",
		"http://www.bluestone.com/"
	]
	rules = (
		Rule(SgmlLinkExtractor(deny=('\+',)), callback='parse_item',follow=True),
	)
#allow=('/pendants/','/rings/','/earrings/','/bangles/',)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	url = response.url
	tester1 = site.select('//form[@id="buy_block"]').extract()
	tester2 = site.select('//h1[@class="title"]/text()').extract() 
	if len(tester1) > 0 and len(tester2) > 0:
		item['link'] = response.url
		item['title'] = site.select('//h1[@class="title"]/text()').extract()[0]

		item['siteId'] = 'bluestone'
		#price
		item['price'] = trimPrice(str(site.select('//span[@class="our_price_display"]//span[@id="our_price_display"]/text()').extract()[0]))
		item['markprice'] = trimPrice(str(item['price']))
		
		#description
		description = site.select('//div[@class="description"]').extract()
		if len(description) > 0:
			item['description'] = description[0]

		#images
		images = site.select('//div[@class="slide-thumbs-inner"]/ul/li/a/@href').extract()
		if len(images) > 0:
			images = filter(lambda x:  x.find('javascript:void(0)') < 0, images)
			if len(images) > 0:
				item['image'] = reduce(lambda x, y: x+'$$$'+y, images) 

		#recommendations
		recommendations = site.select('//section[@id="related-products"]//ul[@class="product-list"]/li//a/@href').extract()
		if len(recommendations) > 0:
			item['recid'] = reduce(lambda x, y: x+'$$$'+y, recommendations) 
		
		#buy link
		# cartLink = "http://www.bluestone.com/cart?id_product="
		item['buylink'] = item['link']
		item['delivery'] = site.select('//span[@id="expected_delievery_date"]/text()').extract()[0]
		if site.select('//span[@id="add_to_cart"]').extract():
			item['availability'] = "in stock"
		else:
			item['availability'] = "out of stock"
		#category
		categories = site.select('//div[@id="breadcrumb"]/ul/li/a/text()').extract()
		categories = categories[1:]
		if len(categories) > 0:
			item['category'] = reduce(lambda x, y: x+'$$$'+y, categories) 
		
		#demographics
		isMen =  site.select('//a[@title="Men"]/text()').extract()
		isWomen =  site.select('//a[@title="Women"]/text()').extract()
		isUnisex =  site.select('//a[@title="Unisex"]/text()').extract()
		if len(isMen) > 0 and len(isWomen) == 0 and len(isUnisex) == 0:
			item['demographics'] = "men"
		elif len(isUnisex) > 0 or (len(isMen) > 0 and len(isWomen) > 0):
			item['demographics'] = "unisex"
		else:
			item['demographics'] = "women"
		items.append(item)
		print item
	return items

def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())
