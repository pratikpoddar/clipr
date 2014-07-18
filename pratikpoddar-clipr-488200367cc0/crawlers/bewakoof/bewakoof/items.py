# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/topics/items.html

from scrapy.item import Item, Field

class CliprItem(Item):
	siteId = Field()
	title = Field()
	link = Field()
	discPrice = Field()
	price = Field()
	markprice = Field()
	image = Field()
	description = Field()
	description2 = Field()
	buylink = Field()
	recid = Field()
	category=Field()
	demographics=Field()
	uniqueId=Field()
	availability=Field()
	delivery=Field()
	shippingcost=Field()
