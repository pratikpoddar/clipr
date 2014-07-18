# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'villcart'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['villcart.spiders']
NEWSPIDER_MODULE = 'villcart.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="villcart.xml"
FEED_FORMAT="xml"
LOG_LEVEL="INFO"
DOWNLOAD_DELAY=1
ITEM_PIPELINES = [
    'villcart.pipelines.ValidatorPipeline',
]
