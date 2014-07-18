# Scrapy settings for loveit2 project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/topics/settings.html
#

BOT_NAME = 'sports365'
BOT_VERSION = '1.0'

SPIDER_MODULES = ['sports365.spiders']
NEWSPIDER_MODULE = 'sports365.spiders'
USER_AGENT = '%s/%s' % (BOT_NAME, BOT_VERSION)
FEED_URI="sports365.xml"
FEED_FORMAT="xml"
LOG_LEVEL="DEBUG"
REDIRECT_ENABLED=True
DOWNLOADER_MIDDLEWARES = {
	'scrapy.contrib.downloadermiddleware.redirect.RedirectMiddleware':123,
}
ITEM_PIPELINES = [
    'sports365.pipelines.ValidatorPipeline',
]
