# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/topics/item-pipeline.html

from scrapy.exceptions import DropItem
from HTMLParser import HTMLParser

class MLStripper(HTMLParser):
    def __init__(self):
        self.reset()
        self.fed = []
    def handle_data(self, d):
        self.fed.append(d)
    def get_data(self):
        return ''.join(self.fed)

#disabled:
#gather as much information as you can
def strip_tags(html):
    return html
    s = MLStripper()
    s.feed(html)
    return s.get_data()

class Crawler2Pipeline(object):
    def process_item(self, item, spider):
        return item

def trimPrice(price):
	return price.replace("Rs",'').replace(',','').replace('.','').strip()
class ValidatorPipeline(object):
	

    def process_item(self, item, spider):
        if (not (item['price'])) or (not (str(trimPrice(item['price'])).isdigit())):
            raise DropItem("price unstructured/undefined in %s" % item)
        else:
            item['price'] = str(trimPrice(item['price']))
        if (not (item['markprice'])) or (not (str(trimPrice(item['markprice'])).isdigit())):
            raise DropItem("markprice unstructured/undefined in %s" % item)
        else:
            item['markprice'] = str(trimPrice(item['markprice']))
        if (not item['description']) and (not item['description2']):
            raise DropItem("Missing descriptions in %s" % item)
        elif item['description']:
            item['description'] = strip_tags(item['description']) 
        else:
            item['description2'] = strip_tags(item['description2']) 
        if (not item['image']):
            raise DropItem("Missing image in %s" % item)
        if (not item['link']):
            raise DropItem("Missing link in %s" % item)
        if (not item['title']):
            raise DropItem("Missing title in %s" % item)
        return item
