import urlparse
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from hitplay.items import CliprItem
#Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
    name = "hitplay"
    allowed_domains = ["hitplay.in"]
    start_urls = [
        "http://hitplay.in/product_details.asp?id=576&catid="
		"http://hitplay.in/search_products.asp?catid=1",
		"http://hitplay.in/search_products.asp?catid=2",
		"http://hitplay.in/search_products.asp?catid=3",
		"http://hitplay.in/search_products.asp?catid=4",
		"http://hitplay.in/search_products.asp?catid=5",
		"http://hitplay.in/search_products.asp?catid=6",
		"http://hitplay.in/search_products.asp?catid=7",
		"http://hitplay.in/search_products.asp?catid=8",
		"http://hitplay.in/search_products.asp?catid=9",
		"http://hitplay.in/search_products.asp?catid=10",
		"http://hitplay.in/search_products.asp?catid=11",
		"http://hitplay.in/search_products.asp?catid=12",
		"http://hitplay.in/search_products.asp?catid=13",
		"http://hitplay.in/search_products.asp?catid=14",
		"http://hitplay.in/search_products.asp?price_one=500&price_two=999",
		"http://hitplay.in/search_products.asp?price_one=1000&price_two=2999",
		"http://hitplay.in/search_products.asp?price_one=3000&price_two=5999",
		"http://hitplay.in/search_products.asp?price_one=6000&price_two=0",
		"http://hitplay.in/search_products.asp?forwho=Him",
		"http://hitplay.in/search_products.asp?forwho=Her",
		"http://hitplay.in/"
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(allow=('/product_details.asp',)), callback='parse_item',follow=True),
        Rule(SgmlLinkExtractor(allow=('/search_products.asp',)), follow=True),
    )

    def parse_item(self, response):
		return parser(response)

def parser(response):
	site = HtmlXPathSelector(response)
	items = []
	item = CliprItem()
	url = response.url
	str1 = url.split('?id=')
	str2 = url.split('&id=')
	currId = -1
	if url.find('?id=') >= 0:
		currId = int((url.split('?id=')[1].split('&'))[0])
	elif url.find('&id=') >= 0:
		currId = int((url.split('&id=')[1].split('&'))[0])

	if currId not in visitedIds:
		visitedIds.add(currId)
		item['link'] = response.url
		item['siteId'] = 'hitplay'
		item['title'] = site.select('//td[@class="product_name"]/text()').extract()[1]
		price = site.select('//td[@class="price"]/text()').extract()[0]
		item['price'] = str(int(price.replace("Rs.","").replace(",","").strip().split('.')[0]))
		markprice = site.select('//span[@class="strike"]/text()').extract()
		if len(markprice) > 0:
			item['markprice'] = str(int(markprice[0].replace("Rs.","").replace(",","").strip().split('.')[0]))
		else:
			item['markprice'] = item['price']
		descriptions = site.select('//td[@class="product_descp"]').extract()
		desc = descriptions[1]
		desc2 = ""
		if len(descriptions) > 2:
			desc2 = site.select('//td[@class="product_descp"]').extract()[2]
		item['description'] = desc.encode('ascii','ignore')+"<description2>"+desc2.encode('ascii','ignore')+"</description2>"

		availability = site.select('//img[@src="images/in_stock.jpg"]')
		if availability:
			item['availability'] ="in stock"
		else:
			item['availability'] ="out of stock"
		item['shippingcost'] = "standard shipping is free"
		item['delivery'] = ''

		images = site.select('//img[@width="500"]/@src').extract()
		if len(images) > 0:
			images = map(lambda x: "http://hitplay.in/"+x, images)
			item['image'] = reduce((lambda x,y: x+'$$$'+y), images)
		else:
			imageBackup = site.select('//td/img[@width="300"]/@src').extract()
			if len(imageBackup) == 1:
				item['image'] = imageBackup[0]
		item['category'] = "gadgets"
		parsed = urlparse.urlparse(url)
		args = urlparse.parse_qs(parsed.query)
		if args.get('forwho') and args['forwho'][0] == "Her":
				item['demographics']="women"
		if args.get('forwho') and args['forwho'][0] == "Him":
				item['demographics']="men"
		item['buylink'] = item['link']
		item['recid'] = ""
		items.append(item)
	return items
