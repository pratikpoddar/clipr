from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from craftsvilla.items import CliprItem
#Completed
class DmozSpider(CrawlSpider):

	name = "craftsvilla"
	allowed_domains = ["craftsvilla.com"]
	start_urls = [
		"http://www.craftsvilla.com/directory/currency/switch/currency/INR/uenc/aHR0cDovL3d3dy5jcmFmdHN2aWxsYS5jb20vZ3JlZW5hcnRzLw,,/",
	]
	rules = (
		#avoiding change of currency while crawling to ensure the price is always in one currency
		Rule(SgmlLinkExtractor(allow=('(?<=page)=','(^((?!=).)*$)',),deny=('/AUD/','/USD/','/EUR/','/GBP/','/CAD/','/SGD/','/MYR/',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)

def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//div[@class="detailCotainer"]')
	if len(tester) > 0:

		#link
		item['link'] = response.url

		#title
		item['title'] = site.select('//div[@class="detailCotainer"]//div[@class="details"]/p[@class="heading_css"]/text()').extract()[0]
		
		#siteId
		item['siteId']=str(4)
		
		#Price is specified either as (mark price,disc price) or as a single price in case of products that are not discounted
		discPrice = site.select('//div[@class="detailCotainer"]//div[@id="dtlPrice"]//div[@class="products price-box"]/p[@class="special-price"]/span[@class="price"]/text()').extract()
		markPrice = site.select('//div[@class="detailCotainer"]//div[@id="dtlPrice"]//div[@class="products price-box"]/p[@class="old-price"]/span[@class="price"]/text()').extract()
		price = site.select('//div[@class="detailCotainer"]//div[@id="dtlPrice"]//div[@class="products price-box"]/span[@class="regular-price"]/span/text()').extract()
	
		if len(markPrice) == 0:
			item['price'] = str(trimPrice(price[0]))
			item['markprice'] = item['price']
		else:
			item['price'] = str(trimPrice(discPrice[0]))
			item['markprice'] = str(trimPrice(markPrice[0]))
		
		#list of images
		images = site.select('//div[@class="product-img-box"]//div[@class="product-img-more"]//li/a/@href').extract()
		#serialized images
		item['image'] = reduce(lambda x, y: x+'$$$'+y, images)
		
		#some kaam chalaau work here. basically removing some useless text. should be moved to pipeline ideally
		description = site.select('//div[@id="dtlText"]/div[@class="detailsnew"]').extract()[0].split('<div class="detailsnew">')[1].split('</div>')[0]
		description2 = site.select('//div[@class="detailCotainer"]//div[@class="details"]/p[@class="sort_description"]').extract()[0]
		item['description'] = description + "<description2>" + description2 + "</description2>"

		# buylink
		item['buylink'] = filterBuylink(site.select('//form[@id="product_addtocart_form"]/@action').extract()[0])
		shipcost = site.select('//ul[@class="shipcost"]/li').extract()
		item['shippingcost'] = getShipCost(shipcost)
		item['availability'] = "in stock"
		item['delivery'] = "10 working days for domestic orders and 21 working days for international orders"
		# breadcrumbs
		category = site.select('//div[@class="breadcrumbs"]/ul/li/a/text()').extract()
		if len(category) > 1:
			category = category[1:]
			product = site.select('//div[@class="breadcrumbs"]/ul/li/h1/text()').extract()
			if len(product) > 0:
				category = category.append(product)
			item['category'] = reduce(lambda x, y: x+'$$$'+y, category )
		else:
			item['category'] = u'crafts'
		# recommendations are not product based
		item['recid'] = ''
		items.append(item)
	return items

def trimPrice(price):
	return int(price.replace("Rs.",'').replace(',','').replace('Rs','').replace('.00','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()

def filterBuylink(buylink):
	tokens = buylink.split('/uenc/')
	return tokens[0] + '/product' + tokens[1].split('/product')[1]
def getShipCost(shipcost):
	shipcost = filter(lambda x: x.find('similar products from this')<0, shipcost)
	return '<ul>' + reduce(lambda x,y: x+y,shipcost) + '</ul>'
