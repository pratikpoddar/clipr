# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'adventure18'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['adventure18.spiders']
NEWSPIDER_MODULE = 'adventure18.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="adventure18.xml"
FEED_FORMAT="xml"
LOG_LEVEL="INFO"
DOWNLOAD_DELAY=1
ITEM_PIPELINES = [
    'adventure18.pipelines.ValidatorPipeline',
]
