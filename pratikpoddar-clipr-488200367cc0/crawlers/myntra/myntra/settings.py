# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'myntra'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['myntra.spiders']
NEWSPIDER_MODULE = 'myntra.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="myntra.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
ITEM_PIPELINES = [
    'myntra.pipelines.ValidatorPipeline',
]
