# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'urbantouch'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['urbantouch.spiders']
NEWSPIDER_MODULE = 'urbantouch.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="urbantouch.xml"
FEED_FORMAT="xml"
LOG_LEVEL="INFO"
ITEM_PIPELINES = [
    'urbantouch.pipelines.ValidatorPipeline',
]
