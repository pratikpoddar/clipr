# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'bewakoof'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['bewakoof.spiders']
NEWSPIDER_MODULE = 'bewakoof.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="bewakoof.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
ITEM_PIPELINES = [
    'bewakoof.pipelines.ValidatorPipeline',
]
