from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from roomstory.items import CliprItem

class DmozSpider(CrawlSpider):
	name = "roomstory"
	allowed_domains = ["roomstory.com"]
	start_urls = [
		"http://www.roomstory.com/",
		"http://www.roomstory.com/decor-furnishings/wall-art-coverings/wall-decals.html",
		"http://www.roomstory.com/decor-furnishings/wall-art-coverings/wall-decor.html",
		"http://www.roomstory.com/decor-furnishings/wall-art-coverings/wall-art.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/candles.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/table-decorative-bowls-baskets.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/statues-figurines-sculptures.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/bookends.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/vases.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/photo-frames.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/candle-holders.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/ashtray.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/artificial-flowers.html",
		"http://www.roomstory.com/decor-furnishings/decorative-accessories/decorative-boxes.html",
		"http://www.roomstory.com/decor-furnishings/lighting/wall-lighting.html",
		"http://www.roomstory.com/decor-furnishings/lighting/ceiling-lighting.html",
		"http://www.roomstory.com/decor-furnishings/lighting/lamp-shades.html",
		"http://www.roomstory.com/decor-furnishings/rugs-mats-carpets/mats.html",
		"http://www.roomstory.com/decor-furnishings/rugs-mats-carpets/carpets.html",
		"http://www.roomstory.com/decor-furnishings/rugs-mats-carpets/rugs.html",
		"http://www.roomstory.com/decor-furnishings/clocks/wall-clocks.html",
		"http://www.roomstory.com/decor-furnishings/window-furnishings-curtains-drapes/curtains-drapes.html",
		"http://www.roomstory.com/decor-furnishings/fragrances/incense-sticks-cones.html",
		"http://www.roomstory.com/decor-furnishings/fragrances/fragrance-sets.html",
		"http://www.roomstory.com/decor-furnishings/fragrances/potpourri.html",
		"http://www.roomstory.com/decor-furnishings/fragrances/pooja-thali.html",
		"http://www.roomstory.com/decor-furnishings/cushions-throws/decorative-cushions.html",
		"http://www.roomstory.com/kitchen-dining/tableware.html",
		"http://www.roomstory.com/kitchen-dining/drinkware-glassware.html",
		"http://www.roomstory.com/kitchen-dining/cookware.html",
		"http://www.roomstory.com/kitchen-dining/bakeware.html",
		"http://www.roomstory.com/kitchen-dining/barware-wine-accessories.html",
		"http://www.roomstory.com/kitchen-dining/cutlery.html",
		"http://www.roomstory.com/kitchen-dining/small-appliances.html",
		"http://www.roomstory.com/kitchen-dining/serving-ware.html",
		"http://www.roomstory.com/kitchen-dining/kitchen-storage.html",
		"http://www.roomstory.com/bed-bath/sheets-bedding-sets.html",
		"http://www.roomstory.com/bed-bath/bath-towels-washclothes.html",
		"http://www.roomstory.com/bed-bath/bath-rugs-mats.html",
		"http://www.roomstory.com/bed-bath/bath-accessories.html",
		"http://www.roomstory.com/bed-bath/bedroom-accessories.html",
		"http://www.roomstory.com/decor-furnishings/wall-art-coverings/wall-decals.html"
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(deny=('/checkout/cart','/customer/account','about-us','media-coverage','/work/','/payments/','returns-and-exchanges','order-and-shipping','/faq','/feedback','contact-us','terms-and-conditions','security','privacy','/blog','/review/product/list/id/',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//div[@class="texthold"]')
	if len(tester) > 0:
		# link
		item['link'] = response.url

		# title
		item['title'] = site.select('//div[@class="texthold"]//h1/text()').extract()[0]

		# price
		price = site.select('//div[@class="texthold"]//div[@class="price-box"]//span[@class="regular-price"]/span/text()').extract()

		# markprice
		markprice = site.select('//div[@class="texthold"]//div[@class="price-box"]//p[@class="old-price"]/span/text()').extract()

		# discounted price
		discprice = site.select('//div[@class="texthold"]//div[@class="price-box"]//p[@class="special-price"]/span/text()').extract()
		if len(price) > 0:
			item['price'] = str(trimPrice(price[0]))
			item['markprice'] = str(trimPrice(price[0]))
		else:
			item['price'] = str(trimPrice(discprice[0]))
			item['markprice'] = str(trimPrice(markprice[0]))

		# images
		images = site.select('//div[@class="tangol-small"]/ul/li/a/@href').extract()
		if not images:
			images = site.select('//div[@class="more-views"]/ul/li/a/@href').extract()
		item['image'] = reduce( lambda x,y: x+'$$$'+y, images)

		# description
		item['description'] = map ( lambda x: trim(x), site.select('//div[@id="data_overview"]/text()').extract())[0]

		# availability and delivery
		prodInfos = site.select('//table[@class="product-data"]/tr')
		prodDict = {}
		for prodInfo in prodInfos :
			arr = prodInfo.select('.//td/text()').extract()
			if len(arr) >= 3:
				if arr[2].strip():
					prodDict[arr[0]] = arr[2]
				else:
					prodDict[arr[0]] = prodInfo.select('.//td/span/text()').extract()[0]

		item['delivery'] = prodDict['Delivery Time']
		item['availability'] = prodDict['Availability']
		# buylink
		item['buylink'] = removeCookieString(site.select('//form[@id="product_addtocart_form"]/@action').extract()[0])

		# category
		breadcrumbs = site.select('//div[@class="breadcrumbs"]/ul/li/a/text()').extract()
		if len(breadcrumbs) > 1:
			breadcrumbs = map( lambda x: trim(x), breadcrumbs[1:])
			item['category'] =  reduce( lambda x,y: x+'$$$'+y, breadcrumbs)
		else:
			item['category'] = ''

		# recommentdations
		recommendations = site.select('//h4[@class="product-name"]/a/@href').extract()
		if recommendations:
			item['recid'] = reduce( lambda x,y: x+'$$$'+y, recommendations)
		else:
			item['recid'] = ''
		items.append(item)
	return items

def removeCookieString(buylink):
	buyLinkTokens = buylink.split('/')
	return reduce( (lambda x,y: x+'/'+y), buyLinkTokens[:6] + buyLinkTokens[8:])
def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()