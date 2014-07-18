# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'jabong'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['jabong.spiders']
NEWSPIDER_MODULE = 'jabong.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="jabong2.xml"
FEED_FORMAT="xml"
LOG_LEVEL="INFO"
ITEM_PIPELINES = [
    'jabong.pipelines.ValidatorPipeline',
]
