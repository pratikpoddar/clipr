from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from sports365.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
    name = "sports365"
    allowed_domains = ["sports365.in"]
    start_urls = [
	"http://www.sports365.in/Products/Buy-Racquet-Sports-Squash-Racquets/Prince/Prince-TF-Storm-Squash-Racquet/pid-1196419.aspx"
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(deny=('/Registration',
        	'/pages','/Sitemap','/sitemap','/ErrorDetails',
        	)), callback='parse_item',follow=True),
    )

    def parse_item(self, response):
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		tester = site.select('//div[@itemprop="name"]/h1/text()').extract()
		if len(tester) > 0:
			#link
			item['link'] = response.url
			
			#title
			item['title'] = site.select('//div[@itemprop="name"]/h1/text()').extract()[0]
			
			#markprice and price

			markprice= site.select('//span[@class="mrp"]//span/text()').extract()[0]
			price = site.select('//span[@class="offer"]//span[@itemprop="price"]/text()').extract()[0]
			item['price'] = (price.replace('.00','')).replace(',','')
			if len(markprice) > 0:
				item['markprice'] = markprice
			else:
				item['markprice'] = price

			#description
			item['description'] = site.select('//div[@id="Description"]').extract()[0]

			#images
			images = site.select('//div[class="imgthumbnail"]/a/img/@src').extract()
			images = map(lambda x: x.replace('Thumbnail','Zoom'), images)
			if len(images) >0:
				item['image'] = reduce((lambda x, y: x+ '$$$' + y), images )
			else:
				item['image'] = ''

			#recommendations dont exist for this site
			recommendations = site.select('//div[@class="bucketgroup"]/div[@class="bucket"]/div[@class="bucket_left"]/a/@href').extract()
			recommendations = map(lambda x: 'http://www.sports365.in'+x, recommendations)
			item['recid'] = ""
			if len(recommendations) > 0:
				item['recid'] = (reduce(lambda x,y:x+'$$$'+y, recommendations))
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categoryList = site.select('div[@class="breadcrumlnk"]/a/text()').extract()
			categoryList = filter(lambda x: x!= 'Home' and x != 'Buy', categoryList)
			if len(categoryList)>0:
				item['category'] = reduce(lambda x,y:x+'$$$'+y, categoryList)
			else:
				item['category'] =""
			items.append(item)
		
		return items
