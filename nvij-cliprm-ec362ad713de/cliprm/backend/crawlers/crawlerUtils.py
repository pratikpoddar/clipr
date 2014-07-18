#!/usr/bin/env python
from django.db.models import F
from mobile.models import Deletedproducts, Productdetail
import traceback
import json
import sys
import os
import cookielib
import urllib2
from datetime import datetime, date, timedelta
from imageWriter import writeImages
# scrapy imports
from scrapy.spider import BaseSpider
from scrapy.http import Request, TextResponse, Response
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor

def getCraftsVillaResponse(link):
	cookies = cookielib.LWPCookieJar()
	handlers = [
		urllib2.HTTPHandler(),
		urllib2.HTTPSHandler(),
		urllib2.HTTPCookieProcessor(cookies)
		]
	opener = urllib2.build_opener(*handlers)
	req = urllib2.Request("http://www.craftsvilla.com/directory/currency/switch/currency/INR/uenc/aHR0cDovL3d3dy5jcmFmdHN2aWxsYS5jb20vZ3JlZW5hcnRzLw,,/")
	opener.open(req)
	req = urllib2.Request(link)
	return opener.open(req).read()

def getAllLinks(link, forceRefresh, site):
	if not link:
		if forceRefresh ==  "True":
			return Productdetail.objects.filter(siteid=site).values('productid','link')
		else:
			deleted_from_site = Deletedproducts.objects.filter(product__siteid=site)
			return map(lambda x: {'link':x.product.link,'productid':x.product.productid}, deleted_from_site)
	else:
		return [{'link':link,'productid':0}]

def getResponse(link,site):
	global sp
	if site == "craftsvilla":
		respText = getCraftsVillaResponse(link)
		finalLink=link
	else:
		opener = urllib2.build_opener()
		opener.addheaders = [('User-agent', 'Mozilla/5.0'),('Cookie', 'CurrencyCode=INR')]
		infile = opener.open(link)
		respText = infile.read()
		finalLink = link

	# creating scrapy request and response
	sreq = sp.make_requests_from_url(finalLink)
	return TextResponse(url=finalLink,status=200,body=respText,encoding='utf-8')

def toUtf(string):
	return (unicode(string)).encode("utf-8")

def insertOrUpdate(item, uniqueId, site, forceRefresh, pid=0):
	if pid:
		res=Productdetail.objects.filter(productid=pid)
	else:
		res=Productdetail.objects.filter(siteid=site,uniqueid=uniqueId)
	images = item['image'].split('$$$')
	try:
		isFirst = True
		for image in images:
			if isFirst:
				imagequality = writeImages(image) # an error in downloading an image prevents database from being written/overwritten
				isFirst = False
			else:
				writeImages(image)
		if not item.get('shippingcost'):
			item['shippingcost']=""
		if res:
			productid = res[0].productid
			imgqual = res[0].imagequality
			if imgqual == -1:
				imagequality = imgqual
			Productdetail.objects.filter(productid=productid).update(
				title=toUtf(item['title']), image=toUtf(item['image']), category=toUtf(item['category']),
				price=item['price'], markprice=item['markprice'],
				link=toUtf(item['link']), buylink=toUtf(item['buylink']), description=toUtf(item['description']),
				recid=toUtf(item['recid']), uniqueid=str(uniqueId), siteid=site,
				imagequality=imagequality,availability=toUtf(item['availability']),delivery=toUtf(item['delivery']),
				shippingcost=toUtf(item['shippingcost']),score=F('score')+4
			)
			isNew = False
		else:
			new_entry = Productdetail(
				title=toUtf(item['title']), category=toUtf(item['category']),
				price=item['price'], markprice=item['markprice'], image=toUtf(item['image']),
				link=toUtf(item['link']), buylink=toUtf(item['buylink']), description=toUtf(item['description']),
				recid=toUtf(item['recid']), uniqueid=str(uniqueId), siteid=site,
				imagequality=imagequality,availability=toUtf(item['availability']),delivery=toUtf(item['delivery']),
				shippingcost=toUtf(item['shippingcost']),score=4
			)
			new_entry.save()
			productid = new_entry.productid
			isNew = True
		if Deletedproducts.objects.filter(product__productid=productid).exists():
			Deletedproducts.objects.filter(product__productid=productid).delete()
	except Exception, e:
		if res:
			pid=res[0].productid
			deleted_product = Deletedproducts(product=Productdetail.objects.get(productid=pid))
			deleted_product.save()
		print str(e)
		print "skipping item: " + item['link']
		return {}
	return {'productid': productid, 'isNew': isNew}

sp = BaseSpider('some')


def parseLinkForSite(site, parserFunc, uniqueIdFunc, givenLink = "", forceRefresh = False):
	rows = getAllLinks( givenLink, forceRefresh, site )

	i = 0
	ret = {}
	for row in rows:
		link = row['link']
		pid = row['productid']
		try:
			if(site == "inonit"):
				item = parserFunc(link)[0]
				uniqueId = item['uniqueId']
			else:
				sresp = getResponse(link, site)
				item = parserFunc(sresp)[0]
				uniqueId = uniqueIdFunc(sresp)
		except Exception, e:
			if givenLink:
				return ret
			deleted_product_entry = Deletedproducts(product=Productdetail.objects.get(productid=pid))
			deleted_product_entry.save()
			print str(e)
			print '>>> traceback <<<'
			traceback.print_exc()
			print '>>> end of traceback <<<'
			print "skipping item: " + link
			continue

		ret = insertOrUpdate(item, uniqueId, site, forceRefresh, pid)
		i+=1
		if ( ( i + 1 ) % 5 == 0):
			if ( i % 20 == 0):
				print str(i) + " done"
	return ret

