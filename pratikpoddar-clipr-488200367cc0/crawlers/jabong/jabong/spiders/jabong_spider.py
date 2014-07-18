from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from jabong.items import CliprItem
###Completed
visitedIds = set()
class DmozSpider(CrawlSpider):
    name = "jabong"
    allowed_domains = ["jabong.com"]
    start_urls = [
		"http://www.jabong.com/jewellery/",
		"http://www.jabong.com/men/",
		"http://www.jabong.com/men/shoes/",
		"http://www.jabong.com/men/clothing/",
		"http://www.jabong.com/sports/?gender=Men",
		"http://www.jabong.com/men/bags/",
		"http://www.jabong.com/men/jewellery/",
		"http://www.jabong.com/men/accessories/",
		"http://www.jabong.com/men/beauty/",
		"http://www.jabong.com/home-living/",
		"http://www.jabong.com/women/",
		"http://www.jabong.com/women/shoes/",
		"http://www.jabong.com/women/clothing/",
		"http://www.jabong.com/sports/?gender=Women",
		"http://www.jabong.com/women/bags/",
		"http://www.jabong.com/women/jewellery/",
		"http://www.jabong.com/women/accessories/",
		"http://www.jabong.com/women/beauty/",
		"http://www.jabong.com/home-living/",
		"http://www.jabong.com/kids/",
		"http://www.jabong.com/kids/shoes/",
		"http://www.jabong.com/kids/clothing/",
		"http://www.jabong.com/sports/?gender=Kids",
		"http://www.jabong.com/kids/bags/",
		"http://www.jabong.com/kids/accessories/",
		"http://www.jabong.com/home-living/",
		"http://www.jabong.com/kids/toys/",
		"http://www.jabong.com/kids/toys/",
		"http://www.jabong.com/"
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(allow=('(?<=page)=','(^((?!=).)*$)',),deny=('/sendfriend/','/customer/','(^((?!---).)*(--)((?!---).)*\/$)')), callback='parse_item',follow=True),
    )
#,'(^((?!/customer/).)*$)','(^((?!/sendfriend/).)*$)'
    def parse_item(self, response):
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		tester = site.select('//h1/span[@class="prd-brand"]/text()')
		if len(tester) > 0:
			
			#link
			item['link'] = response.url
			
			#title
			item['title'] = site.select('//h1/span[@class="prd-brand"]/text()').extract()[0] +'\n' +site.select('//h1/span[@class="prd-title fsm"]/text()').extract()[0] 
			
			#markprice and price
			markprice = site.select('//div[@id="price_div"]//div[@class="pd_prd_price_text_simple old"]/text()').extract()
			price = site.select('//div[@id="price_div"]/div[@id="before_price"]//span[@property="gr:hasCurrencyValue"]/text()').extract()

			if len(markprice) > 0:
				item['markprice'] = markprice[0]
				item['price'] = price[0]
			else:
				item['markprice'] = price[0]
				item['price'] = price[0]

			#description
			item['description'] = site.select('//div[@id="fullDesc"]/text()').extract()[0]
			
			#images
			images = site.select('//div[@id="prdMedia"]//ul[@id="productMoreImagesList"]/li/@data-image-big').extract()
			if len(images) > 0:
				item['image'] =  reduce( (lambda x,y: x+'$$$'+y), images) 
			else:
				item['image'] = ''

			#recommendations dont exist for this site
			item['recid'] = ""#site.select('//img[@width="500"]/@src').extract()
			
			#TODO: Finalize whether link is ok for buylink
			#buylink: depends on what size is chosen
			item['buylink'] = item['link']

			#categorizations
			categories = site.select('//div[@class="breadcrumb"]/div[@class="bcr box mbm fsm breadcrumbs"]/ul/li[@class="prs"]/a/text()').extract()
			product = site.select('//div[@class="breadcrumb"]/div/ul/li/span/text()').extract()
			if len(categories) > 1:
			#demographics
				if categories[1].lower() == u'men' or categories[1].lower() == u'women' or categories[1].lower() == u'kids':
					item['demographics'] = categories[1].lower()
				if len(product) > 0:
					categories[len(categories):] = [product[0]]
				if len(categories) > 1:
					item['category'] = reduce((lambda x,y: x+'$$$'+y), categories)
				else:
					item['category'] = categories[0]
			else:
				if len(product) > 0:
					item['category'] = product[0]
				else:
					item['category'] = ''
			items.append(item)
		return items
