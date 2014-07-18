from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from cbazaar.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
	name = "cbazaar"
	allowed_domains = ["cbazaar.in"]
	start_urls = [
		"http://www.cbazaar.in",
	]
	rules = (
		# Extract links matching 'category.php' (but not matching 'subsection.php')
		# and follow links from them (since no callback means follow=True by default).
		Rule(SgmlLinkExtractor(), callback='parse_item',follow=True),
	)

	def parse_item(self, response):
		return parser(response)


def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	tester = site.select('//h2[@itemprop="name"]/text()').extract()
	if len(tester) > 0:
		currId = tester[0]
		if currId not in visitedIds:
			visitedIds.add(currId)
			#link
			item['link'] = response.url
			
			item['siteId'] = 'cbazaar'
			#title
			item['title'] = tester[0]
			
			#markprice and price
			price = site.select('//input[@id="hdnPrice"]/@value').extract()
			if price:
				item['markprice'] = price[0]
				item['price'] = price[0]

			#description
			description = site.select('//div[@class="rightDetails"]/div[@class="moreProd"]').extract()[0]
			item['description'] = description.split('<div class="moreProd">')[1]

			#images
			imagestrings = site.select('//ul[@id="thumblist"]/li/a/@rel').extract()
			if imagestrings:
				images = map(lambda x: getImage(x), imagestrings)
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )

			#recommendations dont exist for this site
			recommendations = site.select('//ul[@class="carouselData"]/li/span/a/@href').extract()
			recommendations = map(lambda x: 'http://www.cbazaar.in'+x, recommendations)
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))

			item['shippingcost'] = 'free of cost'
			item['delivery'] = 'Dispatch Date: ' + site.select('//input[@id="hdnDelDate"]/@value').extract()[0]+'<br/>' +site.select('//div[@class="dispatchdate"]/text()').extract()[0]
			item['availability'] = 'Available'

			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('//div[@class="breadCrum"]/ul/li//a/text()').extract()
			if len(categoryList) > 1:
				categoryList = map(lambda x: x.strip(), categoryList[1:])
				item['category'] = reduce(lambda x,y:x+'$$$'+y, categoryList)
			items.append(item)
	return items

def getImage(imagestring):
	lst = imagestring.split("'")
	lst = filter(lambda x: x != "", lst)
	reldict = {}
	for i in range(0, len(lst), 2):
		reldict[lst[i].replace(':', '').replace(',','').strip()] = lst[i+1].strip()
	return reldict['largeimage']


def trimPrice(price):
	return int(price.replace("Rs",'').replace(".00",'').replace(".",'').replace("/",'').replace(',','').strip())

def trim(input):
	return input.replace('\r','').replace('\t','').replace('\n','').strip()