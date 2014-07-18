#NOTE: SITEID = 10
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from zansaar.items import CliprItem
###Completed


SITEID = 10

class DmozSpider(CrawlSpider):
    name = "zansaar"
    allowed_domains = ["zansaar.com"]
    start_urls = [
		"http://www.zansaar.com/",
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(deny=('itemQuickLook',)), callback='parse_item',follow=True),
    )

    def parse_item(self, response):
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		tester = site.select('//h1[@id="pdp_title"]/text()')
		if len(tester) > 0:
			
			#link
			item['link'] = response.url
			
			#title
			item['title'] = site.select('//h1[@id="pdp_title"]/text()').extract()[0] 
			
			#markprice and price
			priceSection = site.select('//section[@id="pricesection"]/h2')
			markprice = priceSection.select('.//del/text()').extract()
			price = priceSection.select('.//strong/text()').extract()

			item['price'] = price[0]
			if len(markprice) > 0:
				item['markprice'] = markprice[0]
			else:
				item['markprice'] = price[0]

			#description
			item['description'] = site.select('//section[@id="description_text"]').extract()[0]
			
			#images
			images = site.select('//ul[@id="product_more_images"]/li/span/@href').extract()
			if len(images) > 0:
				item['image'] =  reduce( (lambda x,y: x+'$$$'+y), images) 
			else:
				imageMain = site.select('//section[@class="photos"]//img[@id="pdpMainImg"]/@src').extract()
				if imageMain:
					item['image'] = imageMain[0]
				else:
					item['image'] = ''

			#recommendations 
			recommendations = site.select('//section[@class="product-list"]//a/@href').extract()
			if len(recommendations) > 0:
				item['recid'] =  reduce( (lambda x,y: x+'$$$'+y), recommendations) 
			else:
				item['recid'] = ''
			
			#buylink: sends only as a post request. 
			item['buylink'] = item['link']
			item['siteId'] = str(SITEID)
			#categorizations
			categories = site.select('//ul[@class="crumbs"]/li/a/text()').extract()
			product = site.select('//ul[@class="crumbs"]/li/strong/text()').extract()
			if len(categories) > 0:
				if len(product) > 0:
					categories[len(categories):] = [product[0]]
				else:
					categories[len(categories):] = [item['title']]	
				if len(categories) > 1:
					item['category'] = reduce((lambda x,y: x+'$$$'+y), categories)
				else:
					item['category'] = categories[0]
			elif len(product) == 0:
				item['category'] = product[0]
			else:
				item['category'] = ''
			items.append(item)
		return items
