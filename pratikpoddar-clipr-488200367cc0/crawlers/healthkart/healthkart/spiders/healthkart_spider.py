from bs4 import BeautifulSoup
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from healthkart.items import CliprItem
import string
class DmozSpiderHK(CrawlSpider):
    name = "healthkart"
    allowed_domains = ["healthkart.com"]
    start_urls = [
        "http://www.healthkart.com/product/fastrack-p117wh3/EYE297",
        "http://www.healthkart.com/product/aislin-3025-1-gun/EYE477",
		"http://www.healthkart.com/product/aislin-8018-gold/EYE489",
		"http://www.healthkart.com/product/ivy-s-cervirite-softgels/NUT1194",
		"http://www.healthkart.com/product/endolite-left-oa-lite-knee-braces-11-1531-x/ENDO001",
		"http://www.healthkart.com/product/futuro-slim-silhouette-elbow-support/FUTURO006",
		"http://www.healthkart.com/product/golightly-sugarfree-candy-assorted/SWT003",
		"http://www.healthkart.com/product/johnson-johnson-onetouch-ultra-test-strips/DS005",
		"http://www.healthkart.com/nutrition",
		"http://www.healthkart.com/sports",
		"http://www.healthkart.com/diabetes",
		"http://www.healthkart.com/home-devices",
		"http://www.healthkart.com/personal-care",
		"http://www.healthkart.com/beauty",
		"http://www.healthkart.com/parenting",
		"http://www.healthkart.com/services",
		"http://www.healthkart.com/brands",
		"http://www.healthkart.com/nutrition/sports-nutrition",
		"http://www.healthkart.com/nutrition/dietary-supplements",
		"http://www.healthkart.com/nutrition/condition-specific-supplements",
		"http://www.healthkart.com/nutrition/weight-management",
		"http://www.healthkart.com/nutrition/specialty-nutrition",
		"http://www.healthkart.com/nutrition/fitness-accessories",
		"http://www.healthkart.com/services/hair-spa-beauty",
		"http://www.healthkart.com/services/health-cards",
		"http://www.healthkart.com/services/weight-management",
		"http://www.healthkart.com/services/gyms-health-centres",
		"http://www.healthkart.com/services/health-programs",
		"http://www.healthkart.com/services/health-checkups",
		"http://www.healthkart.com/sports/fitness-accessories",
		"http://www.healthkart.com/sports/fitness-equipment",
		"http://www.healthkart.com/sports/sports-equipment",
		"http://www.healthkart.com/sports/apparel",
		"http://www.healthkart.com/sports/footwear",
		"http://www.healthkart.com/diabetes/testing-supplies",
		"http://www.healthkart.com/diabetes/diabetic-food",
		"http://www.healthkart.com/diabetes/devices",
		"http://www.healthkart.com/diabetes/foot-care",
		"http://www.healthkart.com/home-devices/blood-pressure",
		"http://www.healthkart.com/home-devices/respiratory-care",
		"http://www.healthkart.com/home-devices/supports",
		"http://www.healthkart.com/home-devices/rehabilitation-aids",
		"http://www.healthkart.com/home-devices/clinical-supplies",
		"http://www.healthkart.com/home-devices/miscellaneous",
		"http://www.healthkart.com/eye/eyeglasses",
		"http://www.healthkart.com/eye/sunglasses",
		"http://www.healthkart.com/eye/lenses",
		"http://www.healthkart.com/eye/eye-care",
		"http://www.healthkart.com/eye/what-s-new"
		
    ]
    rules = (
        Rule(SgmlLinkExtractor(allow=('/product/', )), callback='parse_item',follow=True),
    )

    def parse_item(self, response):
    	return parser(response)

def parser(response):
		hxs = HtmlXPathSelector(response)
		sites = hxs.select('//div[@class="product_details"]')
		items = []
		for site in sites:
			item = CliprItem()
			item['siteId']=str(1)
			category=site.select('//div[@class="crumb_outer"]/a/text()').extract()
			if len(category) > 1:
				categoryList = map ( lambda x: trim(x), (category[1:]))
				item['category']=reduce(lambda x, y: x+'$$$'+y, categoryList) 
			item['title'] = trim(site.select('//h2[@class="prod_title"]/text()').extract()[0])
			imageHelper = site.select('//div[@class="product_slideshow"]').extract()
			if len(imageHelper) > 0:
				imageHelper2 = HtmlXPathSelector(text=imageHelper[0])
				largeImages = map(lambda x: x.replace('_s.jpg','_l.jpg').replace('_m.jpg','_l.jpg'), imageHelper2.select('//img/@src').extract())
				largeImages = list(set(largeImages))
				item['image'] = reduce(lambda x, y: x+'$$$'+y, largeImages) 
			
			# price of product
			markprice = site.select('//div[@class="product_details"]//div[@class="cut"]/span/text()').extract()
			price = site.select('//div[@class="product_details"]//div[@class="hk"]/span').extract()

			# it might or might not be inside other span so removing html tags from here
			priceSoup = BeautifulSoup(price[0])
			refinedPrice = priceSoup.get_text()

			if markprice:
				item['markprice'] = str(trimPrice(markprice[0]))
			else:
				item['markprice'] = str(trimPrice(refinedPrice))
			item['price'] = str(trimPrice(refinedPrice))

			item['delivery']="dispatch in "+site.select('//span[@title="Delivery time is extra depending on the location"]/text()').extract()[0].strip()
			if site.select('//input[@name="addToCart"]'):
				item['availability']="in stock"
			else:
				item['availability']="out of stock"

			desc1 = site.select('//div[@class="product_details"]/p[2]').extract()
			desc2 = site.select('//div[@id="description"]').extract()
			if len(desc1) > 0:
				item['description']=trim(desc1[0])
			elif len(desc2) > 0:
				item['description']=trim(desc2[0])
			else:
				item['description']=''
			recommendations = site.select('//div[@id="related_products"]//a[@class="prod_link"]/@href').extract()
			recommendations = map(lambda x: "http://www.healthkart.com"+x, recommendations)

			if recommendations:
				item['recid'] = reduce(lambda x,y: x+'$$$'+y, recommendations)
			else:
				item['recid'] = ""


			item['link'] = response.url
			item['buylink'] = ''
			items.append(item)
			print item
		return items

def trimPrice(pr):
	price = str(pr)
	return int(float(trim(price.replace("Rs.",'').replace("Rs",'').replace("/",'').replace(',','').strip())))

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()