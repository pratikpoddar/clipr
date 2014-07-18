from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule
from fnp.items import CliprItem

class DmozSpider(CrawlSpider):
    name = "fnp"
    allowed_domains = ["fnp.com"]
    start_urls = [
        "http://www.fnp.com/flowers/best-sellers/flower-bunches/--clI_2-cI_1020-pCI_1001-.html",
		"http://www.fnp.com/flowers/occasion/condolences/--clI_2-cI_1024-pCI_1004-.html",
		"http://www.fnp.com/flowers/occasion/love/--clI_2-cI_1028-pCI_1004-.html",
		"http://www.fnp.com/flowers/occasion/birthday/--clI_2-cI_1023-pCI_1004-.html",
		"http://www.fnp.com/flowers/car-decor-/--clI_2-cI_1324-.html",
		"http://www.fnp.com/flowers/flowers-by-numbers/--clI_2-cI_1513-.html",
		"http://www.fnp.com/flowers/roses/--clI_2-cI_1343-.html",
		"http://www.fnp.com/flowers/designer-packing-bouquet/--clI_2-cI_1512-.html",
		"http://www.fnp.com/flowers/special-shape-cake-/--clI_2-cI_1321-.html",
		"http://www.fnp.com/flowers/best-sellers/cake---chocolate/--clI_2-cI_1019-pCI_1001-.html",
		"http://www.fnp.com/flowers/best-sellers/wine-hampers/--clI_2-cI_1016-pCI_1001-.html",
		"http://www.fnp.com/flowers/best-sellers/arrangements/--clI_2-cI_1014-pCI_1001-.html",
		"http://www.fnp.com/flowers/best-sellers/glass-vase-arrangements/--clI_2-cI_1017-pCI_1001-.html",
		"http://www.fnp.com/flowers/best-sellers/flower-combo/--clI_2-cI_1123-pCI_1001-.html",
		"http://www.fnp.com/flowers/occasion/congratulations/--clI_2-cI_1025-pCI_1004-.html",
		"http://www.fnp.com/flowers/car-decor-/--clI_2-cI_1324-.html"
    ]
    rules = (
        # Extract links matching 'category.php' (but not matching 'subsection.php')
        # and follow links from them (since no callback means follow=True by default).
        Rule(SgmlLinkExtractor(), callback='parse_item',follow=True),
    )

    def parse_item(self, response):
		site = HtmlXPathSelector(response)
		items = []
		item = CliprItem()
		tester = site.select('//div[@class="productDesc"]')
		if len(tester) > 0:
			item['link'] = response.url
			item['title'] = site.select('//div[@class="productDesc"]/div[@class="productName"]/strong/text()').extract()
			item['price'] = site.select('//div[@class="productDesc"]//div[@id="itemPriceDiv"]//div[@class="offerPriceDiv"]/span/text()').extract()[1]
			item['markprice'] = ""#site.select('//div[@class="texthold"]//div[@class="price-box"]//p[@class="old-price"]/span/text()').extract()
			item['discPrice'] = ""#site.select('//div[@class="texthold"]//div[@class="price-box"]//p[@class="special-price"]/span/text()').extract()
			item['image'] = site.select('//div[@class="prodImgDivWidth"]//img/@src').extract()
			item['description'] = site.select('//div[@class="allDesc"]//span/text()').extract()[0]
			item['buylink'] = ""#site.select('//div[@id="mprod-buy-btn"]/a/@href').extract()
			rec = site.select('//div[@class="people_viewed"]').extract()
			if len(rec) > 0:
				recSelector = HtmlXPathSelector(text=rec[0])
				item['recid'] = recSelector.select('//a[@class="product-image"]/@href').extract()
			items.append(item)
		return items
		