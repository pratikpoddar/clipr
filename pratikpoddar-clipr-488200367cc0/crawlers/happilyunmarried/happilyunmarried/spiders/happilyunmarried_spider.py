from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from happilyunmarried.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "happilyunmarried"
	allowed_domains = ["happilyunmarried.com"]
	start_urls = [
	"http://www.happilyunmarried.com/products.html",
	"http://www.happilyunmarried.com/products/new.html",
	"http://www.happilyunmarried.com/products/best-sellers.html",
	"http://www.happilyunmarried.com/products/bar-and-drinking.html",
	"http://www.happilyunmarried.com/products/home-and-kitchen.html"
	"http://www.happilyunmarried.com/products/tee-shirts.html"
	"http://www.happilyunmarried.com/products/cards-posters.html",
	"http://www.happilyunmarried.com/products/bags.html",
	"http://www.happilyunmarried.com/products/lamps.html",
	"http://www.happilyunmarried.com/products/gift-vouchers.html",
	"http://www.happilyunmarried.com/products/u-f-o-s.html",
	"http://www.happilyunmarried.com/products/stationery.html",
	"http://www.happilyunmarried.com/products/doormats.html"
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(allow=('/products/',), deny=('/checkout/','/wishlist/',)), callback='parse_item',follow=True),
		# Rule(SgmlLinkExtractor(deny=('/checkout/','/wishlist/',)),follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	# tester = site.select('//div[@class="happy-product-image"]').extract()
	currId = (site.select('//input[@name="product"]/@value').extract())
	if currId:
		currId = int(currId[0])
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			#siteId
			item['siteId'] = 'happilyunmarried'
			#title
			item['title'] = site.select('//h1[@class="happy-title"]/text()').extract()[0]
			
			#markprice and price
			markprice = site.select('//span[@class="regular-price"]/span[@class="price"]/text()').extract()
			if len(markprice) > 0:
				markprice = markprice[0].replace('Rs.','').replace('.00','').replace(',','').strip()
				if markprice.isdigit():
					item['price'] = markprice
					item['markprice'] = markprice	
			else:
				markprice = site.select('//div[@class="price-box"]/p[@class="discount-price"]/span[@class="old"]/text()').extract()
				discountprice = site.select('//div[@class="price-box"]/p[@class="discount-price"]/span[@class="price"]/text()').extract()
				if markprice:
					markprice = markprice[0].replace('Rs.','').replace('.00','').replace(',','').strip()
					discountprice = discountprice[0].replace('Rs.','').replace('.00','').replace(',','').strip()
					if markprice.isdigit():
						item['markprice'] = markprice	
					if discountprice.isdigit():
						item['price'] = discountprice

			item['availability'] = 'in stock'
			item['delivery'] = 'dispatched in 2-3 days'
			#description
			descriptionList1 = site.select('//form[@id="product_addtocart_form"]/p').extract()
			descriptionList2 = site.select('//form[@id="product_addtocart_form"]/text()').extract()
			description = reduce( ( lambda x,y: x+y ), ( descriptionList1 + descriptionList2 ) )
			item['description'] = description.strip()

			#images
			mainImage = site.select('//div[@class="happy-product-image"]/a/@href').extract()[0]
			additionalImages = site.select('//div[@class="happy-more-views"]/ul/li/a/@href').extract()
			imageList = []
			if len(additionalImages) > 0:
				additionalImages.insert(0, mainImage)
				imageList = additionalImages
			else:
				imageList = [mainImage]
			item['image'] = reduce((lambda x, y: x+ '$$$' + y), imageList )

			#no recommendations for this site 
			item['recid'] = ""

			#buylink would redirect to page if additional info is required
			buylink = site.select('//form[@id="product_addtocart_form"]/@action').extract()[0]
			buylink = filterBuylink(buylink)
			item['buylink'] = buylink

			#categorizations
			categoryList = site.select('//div[@class="breadcrumbs"]/ul/li/a/text()').extract()
			categoryList = filter(lambda x: x != 'Happily Unmarried' and x != 'Products', categoryList)
			if len(categoryList)>0:
				item['category'] = reduce((lambda x, y: x+ '$$$' + y), categoryList )
			else:
				item['category'] = ""
			item['demographics'] = ""
			items.append(item)
			# print item
	
	return items
def filterBuylink(buylink):
	tokens = buylink.split('/uenc/')
	return tokens[0] + '/product' + tokens[1].split('/product')[1]
