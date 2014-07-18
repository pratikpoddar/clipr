# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/topics/item-pipeline.html

from scrapy.exceptions import DropItem

class CliprPipeline(object):
    def process_item(self, item, spider):
        return item

def trimPrice(price):
	return price.replace("Rs",'').replace(',','').replace('.','').strip()
class ValidatorPipeline(object):
	

    def process_item(self, item, spider):
        if (not (item['price'])) or (not (str(trimPrice(item['price'])).isdigit())):
            raise DropItem("price unstructured/undefined in %s" % item['link'])
        if (not (item['markprice'])) or (not (str(trimPrice(item['markprice'])).isdigit())):
            raise DropItem("markprice unstructured/undefined in %s" % item['link'])
        if (not item['description']) and (not item['description2']):
        	raise DropItem("Missing descriptions in %s" % item['link'])
        if (not item['image']):
			raise DropItem("Missing image in %s" % item['link'])
        if (not item['link']):
			raise DropItem("Missing link in %s" % item['link'])
        if (not item['title']):
			raise DropItem("Missing title in %s" % item['link'])
        if (not item['siteId']):
            raise DropItem("Missing siteId in %s" % item['link'])
        return item
