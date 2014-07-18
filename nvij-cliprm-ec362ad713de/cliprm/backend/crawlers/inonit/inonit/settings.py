# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'inonit'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['inonit.spiders']
NEWSPIDER_MODULE = 'inonit.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="inonit.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
ITEM_PIPELINES = [
    'inonit.pipelines.ValidatorPipeline',
]
