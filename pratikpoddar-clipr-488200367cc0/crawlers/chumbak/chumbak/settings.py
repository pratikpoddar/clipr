# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'chumbak'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['chumbak.spiders']
NEWSPIDER_MODULE = 'chumbak.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="chumbak.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
ITEM_PIPELINES = [
    'chumbak.pipelines.ValidatorPipeline',
]
