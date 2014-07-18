from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from infibeam.items import CliprItem

visitedLinks = set()
class DmozSpider(CrawlSpider):
    name = "infibeam"
    allowed_domains = ["infibeam.com"]
    start_urls = [
		"http://www.infibeam.com/Books/",
		"http://www.infibeam.com/Gifts/",
		"http://www.infibeam.com/Computers_Accessories/",
		"http://www.infibeam.com/Cameras/",
		"http://www.infibeam.com/Watches/",
		"http://www.infibeam.com/hot-deals.html",
		"http://www.infibeam.com/eBooks/",
		"http://www.infibeam.com/Magazines/",
		"http://www.infibeam.com/eMagazines/",
		"http://www.infibeam.com/Pi",
		"http://www.infibeam.com/Mobiles/",
		"http://www.infibeam.com/Portable_Electronics/search?bodyType=Tablet",
		"http://www.infibeam.com/Mobile_Accessories/",
		"http://www.infibeam.com/Mobiles/search",
		"http://www.infibeam.com/Apparel/",
		"http://www.infibeam.com/Apparel_Accessories/",
		"http://www.infibeam.com/Jewellery/",
		"http://www.infibeam.com/Footwear/",
		"http://www.infibeam.com/Beauty/",
		"http://www.infibeam.com/Toys_Games/",
		"http://www.infibeam.com/Home-Lifestyle/",
		"http://www.infibeam.com/Gifts/search",
		"http://www.infibeam.com/Accessories_Computers_Accessories/search",
		"http://www.infibeam.com/Home_Appliances/",
		"http://www.infibeam.com/Portable_Electronics/",
		"http://www.infibeam.com/Home_Entertainment/",
		"http://www.infibeam.com/Gaming_Consoles/",
		"http://www.infibeam.com/Camera_Accessories/search",
		"http://www.infibeam.com/Movies/",
		"http://www.infibeam.com/Music/",
		"http://www.infibeam.com/Phi",
		"http://www.infibeam.com/Bikes/",
		"http://www.infibeam.com/Cars/",
		"http://www.infibeam.com/Travel/",
		"http://www.infibeam.com/"
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(allow=('/Books/', '/Gifts/','/Travel/','/Cars/','/Bikes/','/Music/','/Camera_Accessories/',
			'/Portable_Electronics/','/Gaming_Consoles/','/Accessories_Computers_Accessories/','/Mobiles/','/eMagazines/',
			'/eBooks/','/Watches/','/Computers_Accessories/','/Apparel/','/Jewellery/','/Cameras/','/Magazines/',
			'/Mobile_Accessories/','/Apparel_Accessories/','/Footwear/','/Beauty/','/Toys_Games/','/Home-Lifestyle/',
			'/Home_Appliances/','/Movies/',	), deny=('/search',)), callback='parse_item',follow=True),
    )

    def parse_item(self, response):
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		tester = site.select('//div[@class="hproduct"]').extract()
		url = response.url
		linkWithoutParam = url.split('?')[0]
		currLink = linkWithoutParam.split('#')[0]
		if len(tester) > 0:
			if currLink not in visitedLinks:
				#remove duplicates
				visitedLinks.add(currLink)

				#title
				tempTitle = site.select('//h1[@class="fn"]//span/text()').extract()
				if len(tempTitle) > 0:
					item['title'] = tempTitle
				else:
					item['title'] = site.select('//h1[@class="fn"]/text()').extract()
				item['siteId'] = str(5)
				#links
				item['link'] = response.url

				#price and markprice
				markprice = site.select('//span[@class="msrp"]/text()').extract()
				price = site.select('//span[@class="infiPrice amount price"]/text()').extract()
				if len(price)> 0:
					item['price'] = price[0]
					if len(markprice) > 0:
						item['markprice'] = markprice[0]
					else:
						item['markprice'] = item['price']
				else:
					item['price'] = ''
					item['markprice'] =''
				#description very unsystematic
				descriptionRaw = site.select('//div[@id="ib_products"]').extract()#VERY INCONSISTENT
				if len(descriptionRaw) > 0:
					descriptionWithoutRec = descriptionRaw[0].split('<div class="boxouter big">')[0]
					item['description'] = descriptionWithoutRec

				#buylink
				prodId = site.select('//form[@name="AddItemToCart"]/input[@name="listingId"]/@value').extract()
				if len(prodId) > 0:
					item['buylink'] = "http://www.infibeam.com/AddItemToCart.action?listingId=" + prodId[0] + "&quantity=1"

				#images
				images = site.select('//div[@id="ib_img_viewer"]/ul//img/@src').extract()
				images = map((lambda x: (x.split('?'))[0] ), images)
				if len(images) > 0:
					item['image'] = reduce((lambda x,y: x +'$$$'+y), images)
				else:
					item['image'] = ''

				#recommendations
				recommendations = site.select('//div[@class="boxinner big"]/ul/li/a/@href').extract()
				recommendations = map((lambda x: 'http://www.infibeam.com' +x), recommendations)
				if len(recommendations) > 0:
					item['recid'] = reduce((lambda x,y: x +'$$$'+y), recommendations)


				items.append(item)
		return items
