# Scrapy settings for indiatimes project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'indiatimes'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['indiatimes.spiders']
NEWSPIDER_MODULE = 'indiatimes.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="indiatimes.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
ITEM_PIPELINES = [
    'indiatimes.pipelines.ValidatorPipeline',
]
# Crawl responsibly by identifying yourself (and your website) on the user-agent
#USER_AGENT = 'indiatimes (+http://www.yourdomain.com)'
