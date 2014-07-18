from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from fabfurnish.items import CliprItem
#Completed

visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "fabfurnish"
	allowed_domains = ["fabfurnish.com"]
	start_urls = [
		"http://www.fabfurnish.com/furniture/bedroom/",
		"http://www.fabfurnish.com/kids-baby/kids-kitchenware/"
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(deny=('/checkout/cart/','/customer/','/index/','\?price','(http.*http)',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//h1[@class="prd-title-new"]')
	if len(tester) > 0:

		# magic here to get the product-id and 
		prodId = site.select('//input[@id="configSku"]/@value').extract()[0]
		
		prodId2Raw = site.select('//div[@class="l-sidebar"]/div[@data-no_cod and contains(id, prodId)]/@id').extract()[0]
		prodId2Trimmed = prodId2Raw.split(prodId)
		sku = prodId + prodId2Trimmed[len(prodId2Trimmed)-1]
		
		if sku not in visitedIds:

			visitedIds.add(sku)

			#link
			item['link'] = response.url
			
			item['siteId'] = 'fabfurnish'

			#price and markprice
			#either only price is populated(in which case product is essentially selling at markprice) or both markprice and discprice are populated 
			discPrice = site.select('//span[@id="product_special_price"]/text()').extract()
			markPrice = site.select('//span[@id="price_box"]/text()').extract()
			price = site.select('//span[@id="price_box"]/span/text()').extract()

			# price
			if len(price) > 0:
				item['price'] = trimPrice(price[0])
				item['markprice'] = trimPrice(item['price'])
			else:
				item['markprice'] = trimPrice(markPrice[0])
				item['price'] = trimPrice(discPrice[0])

			# title
			item['title'] = site.select('//h1[@class="prd-title-new"]/text()').extract()[0]
			
			# images
			images = site.select('//div[@id="gallery"]//ul[@class="ad-thumb-list"]/li/a[@class="image"]/@href').extract()
			if len(images) > 0:
				item['image'] = reduce(lambda x, y: x+'$$$'+ y, images)
			
			# descriptions
			#2 different form of descriptions given in almost all products
			desc1 = site.select('//div[@class="prd-attr-box bb"]').extract()[0]
			desc2 = site.select('//div[@class="prd-attributes-item prd-attributes-shortDesc prd-attr-box"]').extract()[0]

			item['description'] = desc1 + "<description2>" + desc2 + "</description2>"
			
			buybtn = site.select('//button[@id="AddToCart"]/@title').extract()
			if buybtn and buybtn[0].lower()=="buy now":
				item['availability']="in stock"
			else:
				item['availability']="out of stock"

			shipinfo = site.select('//div[contains(@class,"shipped")]/text()').extract()
			if shipinfo:
				item['delivery']=shipinfo[0]
			else:
				item['delivery']=""
			item['shippingcost']="Shipping free for orders of Rs 100 or more. Charges of Rs 100 for other products"
			#buylink 
			baseBuyLink=  'http://www.fabfurnish.com/cart/add/?p='
			item['buylink'] = baseBuyLink + prodId+'&sku='+sku+'&quantity=1'

			#recommendations
			rec = site.select('//div[@id="p_similarPRD"]').extract()
			if len(rec) > 0:
				recSelector = HtmlXPathSelector(text=rec[0])
				item['recid'] = recSelector.select('//td[@class="similarTDBg"]//a/@href').extract()
			else:
				item['recid'] = ''
			
			#categorizations
			categories = site.select('//div[@class="breadcrumb"]/div/ul/li/a/text()').extract()
			product = site.select('//div[@class="breadcrumb"]/div/ul/li/span/text()').extract()
			if len(categories) > 0:
				if len(product) > 0:
					categories[len(categories):] = [product[0]]
				if len(categories) > 1:
					item['category'] = reduce((lambda x,y: x+'$$$'+y), categories)
				else:
					item['category'] = categories[0]
			else:
				item['category'] = 'home decor'
			items.append(item)
			print item
	return items
	
def trimPrice(pr):
	price = str(pr)
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())
