# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'cbazaar'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['cbazaar.spiders']
NEWSPIDER_MODULE = 'cbazaar.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="cbazaar.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
ITEM_PIPELINES = [
    'cbazaar.pipelines.ValidatorPipeline',
]
