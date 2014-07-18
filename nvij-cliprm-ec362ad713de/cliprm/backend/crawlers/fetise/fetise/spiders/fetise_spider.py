from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from fetise.items import CliprItem


visitedIds = set()
class DmozSpiderHK(CrawlSpider):
	name = "fetise"
	allowed_domains = ["fetise.com"]
	start_urls = [
		"http://www.fetise.com/",
		"http://www.fetise.com/1249-ed-hardy-express-tees"		
	]
	rules = (
		Rule(SgmlLinkExtractor(deny=('&',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	tester = site.select('//div[@class="FloatL PT5 productsHead"]/text()').extract()
	if len(tester) > 0:
		currid = int(response.url.split('/')[4].split('-')[0])
		if currid not in visitedIds: 
			
			visitedIds.add(currid)

			item = CliprItem()
			item['siteId'] = 'fetise'
			item['title'] = site.select('//div[@class="FloatL PT5 productsHead"]/text()').extract()[0].strip()
			imageHelper = site.select('//div[@id="product_thumbs"]').extract()
			imageHelper2 = HtmlXPathSelector(text=imageHelper[0])
			images = imageHelper2.select('//a/img/@src').extract()
			item['image'] = reduce(lambda x,y: x+'$$$'+y, images)

			price = site.select('//div[@class="ourPriceBig PB10"]/text()').extract()
			discprice = site.select('//div[@class="superSaverPriceRed"]/span/text()').extract()
			priceinited = False
			if len(price) > 0:
				price = price[0].replace('Our Price:','')
				if price:
					item['price'] = trimPrice(price)
					priceinited = True
			if ( ( not priceinited ) and len(discprice)> 0 ):
				item['price'] = trimPrice( discprice[0] )
			
			markprice = site.select('//div[@class="MRPbig"]/span/text()').extract()
			if len(markprice) > 0:
				item['markprice'] = markprice[0].replace('MRP:','').replace('(','').replace(')','').strip()
			else:
				item['markprice'] = item['price']

			item['delivery']=site.select('//div[@class="Guarantee PT5"]/text()').extract()[0]
			item['shippingcost']=site.select('//div[@class="FloatL promoCodeBG borderRightWhiteSolid"]').extract()[1]
			item['availability']="in stock"
			item['description'] = site.select('//div[@class="productHighlight"]').extract()[0]
			item['link'] = response.url
			item['category'] = response.url.split('/')[3]
			item['buylink'] = item['link']
			recommendations = site.select('//div[@class="similarProductList"]/div/div/a/@href').extract()
			if len(recommendations) > 0:
				item['recid'] = reduce(lambda x,y: x+'$$$'+y, recommendations)
			else:
				item['recid'] = ''
			print item
			items.append(item)
	
	return items

def trimPrice(price):
	return int(price.replace("Rs.",'').replace(',','').replace('Rs','').replace('.00','').strip())
