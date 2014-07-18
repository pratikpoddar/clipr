from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from adventure18.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "adventure18"
	allowed_domains = ["adventure18.com"]
	start_urls = [
	"http://www.adventure18.com/sitemap-1.html",
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(deny=('\?',)), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//label[@class="quantity_box"]/text()').extract()
	if len(tester) > 0:

		currId = site.select('//div[@id="vmMainPage"]/table/tbody/tr/td/div[@style="float:left;"]/text()').extract()
		currId = filter(lambda x: x.strip() != "", currId)
		currId = currId[0].strip()
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'adventure18'
			#title
			item['title'] = site.select('//h1/text()').extract()[0]
			
			#markprice and price
			price = site.select('//div[@id="vmMainPage"]/table/tbody/tr/td/div[@style="float:left;"]/span/text()').extract()
			price = price[0].replace('INR','').replace('.00','').replace(',','').strip()
			item['markprice'] = price
			item['price'] = price

			#description
			description = site.select('//div[@class="product_sdes"]').extract()[0]
			item['description'] = description

			#images
			item['image'] = site.select('//div[@class="thumb_image"]/a[@class="cloud-zoom"]/@href').extract()[0]

			#recommendations dont exist for this site
			recommendations = site.select('//section[@class="diagonal-divider clearfix"]/ul/li/a/@href').extract()
			recommendations = map(lambda x: 'http://www.adventure18.com'+x, recommendations)
			item['recid'] = ""
			

			item['buylink'] = item['link']

			#categorizations
			categoryList = response.url.split('/')
			if len(categoryList)>5:
				item['category'] = categoryList[4]
			else:
				item['category'] =""
			item['demographics'] = ""
			items.append(item)
	
	return items
