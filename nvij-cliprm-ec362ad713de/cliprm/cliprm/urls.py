from django.views.generic import TemplateView
from django.conf.urls import patterns, include, url
from mobile.views.home import m_trending, d_trending
from mobile.views.cliprtransfer import transfer_to_seller
from mobile.views.product import m_product, d_product
from mobile.views.tagpage import tag_page
from mobile.views.tag import tag_friend
from mobile.views.clip import clip_product
from mobile.views.getfriends import get_friends
from mobile.views.login import login_page
from mobile.views.profile import m_my_clipboard, m_clipboard, m_added_products, m_followers, m_following
from mobile.views.follow import follow_user
from mobile.views.feed import m_my_feed, d_my_feed
from mobile.views.debug import temp
from mobile.views.add import d_add
from mobile.views.addproduct import d_addproduct
from mobile.views.profile import d_my_clipboard, d_clipboard, d_added_products, d_followers, d_following
from mobile.views.update_prefs import change_pref
from mobile.views.clipr_settings import d_my_settings

# Uncomment the next two lines to enable the admin:
# from django.contrib import admin
# admin.autodiscover()

desktop_urls = patterns('',
	url(r'^temp$', temp),
	url(r'^product/(?P<pid>\d+)/.*$', d_product),
	url(r'^product/(?P<pid>\d+)$', d_product),
	url(r'^add$', d_add),
	url(r'^add/(?P<status>.+)$', d_add),
	url(r'^addproduct$', d_addproduct),
	url(r'^$', d_trending),
	url(r'^(?P<uid>\d+)/?$', d_clipboard),
	url(r'^clips$', d_my_clipboard),
	url(r'^(?P<uid>\d+)/followers$', d_followers),
	url(r'^(?P<uid>\d+)/following$', d_following),
	url(r'^(?P<uid>\d+)/added$', d_added_products),
	url(r'^feed/?$', d_my_feed),
	url(r'^settings/?$', d_my_settings),
	url(r'^changeprefs/?$', change_pref),
)

urlpatterns = patterns('',
	# Examples:
	# url(r'^$', 'cliprm.views.home', name='home'),
	# url(r'^cliprm/', include('cliprm.foo.urls')),
	# Uncomment the admin/doc line below to enable admin documentation:
	url(r'^$', m_trending),
	url(r'^changeprefs/?$', change_pref),
	url(r'^feed/?$', m_my_feed),
	url(r'^product/(?P<pid>\d+)/.*$', m_product),
	url(r'^product/(?P<pid>\d+)$', m_product),
	url(r'^cliprtransfer/(?P<pid>\d+)$', transfer_to_seller),
	url(r'^cliprtransfer/(?P<pid>\d+)/.*$', transfer_to_seller),
	url(r'^tagpage$', tag_page),
	url(r'^tag$', tag_friend),
	url(r'^login/$', login_page),
	url(r'^logout/$', 'django.contrib.auth.views.logout',{'next_page': '/'}),
	url(r'^getfriends$', get_friends),
	url(r'^follow$', follow_user),
	url(r'^clip$', clip_product),
	url(r'^clips$', m_my_clipboard),
	url(r'^(?P<uid>\d+)/followers$', m_followers),
	url(r'^(?P<uid>\d+)/following$', m_following),
	url(r'^(?P<uid>\d+)/added$', m_added_products),
	url(r'^(?P<uid>\d+)$', m_clipboard),
	url(r'^(?P<uid>\d+)/.*$', m_clipboard),
	url(r'auth/', include('social_auth.urls')),
	url(r'desktop/', include(desktop_urls)),
	url(r'^error$', TemplateView.as_view(template_name="m_error.html")),
	url(r'^temp$', temp),
	# Uncomment the next line to enable the admin:
	# url(r'^admin/', include(admin.site.urls)),
)

