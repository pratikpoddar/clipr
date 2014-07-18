from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from indiatimes.items import CliprItem

visitedIds = set()
class DmozSpider(CrawlSpider):
    name = "indiatimes"
    allowed_domains = ["shopping.indiatimes.com"]
    start_urls = [
        "http://shopping.indiatimes.com/",
		"http://shopping.indiatimes.com/books/",
		"http://shopping.indiatimes.com/astro-shop/",
		"http://shopping.indiatimes.com/mobiles/",
		"http://shopping.indiatimes.com/computers/",
		"http://shopping.indiatimes.com/cameras/",
		"http://shopping.indiatimes.com/flowers-gifts/",
		"http://shopping.indiatimes.com/electronics/",
		"http://shopping.indiatimes.com/health-beauty/",
		"http://shopping.indiatimes.com/lifestyle/",
		"http://shopping.indiatimes.com/fashion/",
		"http://shopping.indiatimes.com/music/",
		"http://shopping.indiatimes.com/movies/",
		"http://shopping.indiatimes.com/games/",
		"http://shopping.indiatimes.com/baby/"
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(deny=('&filter','&filter=','?filter=','?price=','mobileApps','(^(.*)(&)(.*)$)',)), callback='parse_item',follow=True),
    )

    def parse_item(self, response):
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		url = response.url

		tester = site.select('//div[@class="productdetailwrapper"]').extract()
		if len(tester) > 0:
			item['siteId'] = str(8)
			#title
			item['title'] = site.select('//h1[@itemprop="name"]/text()').extract()[0]

			#link
			item['link'] = response.url

			#category: breadcrumbs
			category=site.select('//div[@class="navigation flt"]/a/text()').extract()
			if len(category) > 0:
				categories = map( lambda x: x.lower().strip(), category[1:] )
				item['category'] = reduce( ( lambda x,y: x + '$$$' + y ), categories )
			else:
				item['category'] = "misc"

			#price and markprice
			markpriceDisplay = site.select('//div[@class="priceshipinfo"]//div[@class="oldprice" and contains(.,"none")]').extract()
			markprice = site.select('//div[@class="priceshipinfo"]//div[@class="oldprice"]/span[@class="price"]/text()').extract()
			price = site.select('//div[@class="priceshipinfo"]//div[@class="newprice"]/span[@class="price"]/text()').extract()
			item['price']= price[0]
			if len(markprice) > 0 and len(markpriceDisplay) < 1:
				item['markprice']= markprice[0]
			else:
				item['markprice']= price[0]

			#description
			desc = site.select('//div[@class="productdetail"]').extract()
			desc2 =  site.select('//div[@class="productspecification"]').extract()
			if len(desc) > 0:
				item['description'] = trim(desc[0])
			elif len(desc2) > 0:
				item['description'] = trim(desc2[0])
			else:
				item['description'] = ""

			#buy link
			itemid = site.select('//form[@name="addform"]/input[@id="add_product_id"]/@value').extract()
			if len(itemid) > 0:
				item['buylink'] = "http://shopping.indiatimes.com/control/additem?add_product_id="+itemid[0]
			else:
				item['buylink'] ="Out of Stock!"
			

			#images
			images = site.select('//div[@class="productimglow"]//ul/li/a/img/@src').extract()
			if len(images) > 0:
				images = map((lambda x: x.replace('small','large')), images)
				item['image'] = reduce((lambda x,y: x+'$$$'+y), images)
			elif len(site.select('//div[@class="productimghigh"]//a/img/@src').extract()) > 0:
				item['image'] = site.select('//div[@class="productimghigh"]//a/img/@src').extract()[0]
			else:
				item['image'] = ''
			
			#recommendations
			recommendations = site.select('//ul[@class="similarproductlist"]/li//div[@class="description frt"]/a/@href').extract()
			recommendations = map((lambda x: 'http://shopping.indiatimes.com' +x), recommendations)
			if len(recommendations) > 0:
				item['recid'] = reduce((lambda x,y: x+'$$$'+y), recommendations)
			else:
				item['recid'] = ''
			items.append(item)

		return items
def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','')
