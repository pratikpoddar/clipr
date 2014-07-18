#!/usr/bin/env python
import traceback
import ConfigParser
import json
import _mysql
import sys
import MySQLdb as mdb
import os
import cookielib
import urllib2
from urllib2 import HTTPError
import urllib
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

def getAllLinks(link, forceRefresh, cursor, site):
	if not link:
		if forceRefresh ==  "True":
			cursor.execute( "SELECT link, productid from productDetail where siteId = %s order by productid;",(site) )
			return cursor.fetchall()
		else:
			cursor.execute( "SELECT link, productid from productDetail where siteId = %s and productid in (select productid from deletedProducts)" ,(site) )
			return cursor.fetchall()
	else:
		return [[link,0]]

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

def insertOrUpdate(item,cursor, uniqueId, site, forceRefresh, pid=0):
	if pid:
		cursor.execute("SELECT productid,imageQuality from productDetail where productid =%s;",str(pid))
	else:
		cursor.execute("SELECT productid,imageQuality from productDetail where uniqueId = %s and siteId =%s;",(str(uniqueId), site))
	res = cursor.fetchall()

	images = item['image'].split('$$$')

	try:
		isFirst = True
		for image in images:
			if isFirst:
				imageQuality = writeImages(image) # an error in downloading an image prevents database from being written/overwritten
				isFirst = False
			else:
				writeImages(image)

		if not item.get('shippingcost'):
			item['shippingcost']=""
		if res:
			productid = res[0][0]
			imgqual = res[0][1]

			if imgqual == -1:
				imageQuality = imgqual

			cursor.execute("UPDATE productDetail SET title = %s, image = %s, category=%s, price=%s,markprice=%s,buylink=%s, \
				link=%s, description = %s, recid=%s , imageQuality = %s, uniqueId = %s, availability = %s, delivery= %s, shippingcost = %s where productid =%s",
				(
					toUtf(item['title']), toUtf(item['image']), toUtf(item['category']), str(item['price']), str(item['markprice']), 
					toUtf(item['buylink']), toUtf(item['link']), toUtf(item['description']), toUtf(item['recid']), imageQuality, str(uniqueId),
					toUtf(item['availability']), toUtf(item['delivery']), toUtf(item['shippingcost']), str(productid)
				)
			)
			isNew = False
		else:
			cursor.execute("INSERT into productDetail(title, image,category,price,markprice,buylink,link,description,recid,uniqueId,siteId,imageQuality,availability,delivery,shippingcost,score)\
				VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
				(
					toUtf(item['title']), toUtf(item['image']), toUtf(item['category']), str(item['price']), str(item['markprice']), 
					toUtf(item['buylink']), toUtf(item['link']), toUtf(item['description']), toUtf(item['recid']), str(uniqueId), site, str(imageQuality),
					toUtf(item['availability']), toUtf(item['delivery']), toUtf(item['shippingcost']), toUtf("4")
				)
			)
			productid = cursor.lastrowid
			isNew = True
		cursor.execute( "DELETE from deletedProducts where productid = %s",( productid ) )
	except Exception, e:
		if res:
			cursor.execute( "INSERT into deletedProducts(productid) values(%s)",( res[0][0] ) )
		print str(e)
		print "skipping item: " + item['link']
		return {}

	return {'productid': productid, 'isNew': isNew}

sp = BaseSpider('some')

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../../db.ini')
	dictionary = {}
	for section in config.sections():
		dictionary[section] = {}
		for option in config.options(section):
			dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

def parseLinkForSite(site, parserFunc, uniqueIdFunc, givenLink = "", forceRefresh = False):
	con = None
	try:
		connectionsettings = getdbsettings()
		con = mdb.connect(host=connectionsettings['server'] ,user=connectionsettings['user'] ,passwd=connectionsettings['password'],db=connectionsettings['db_name'], charset = "utf8", use_unicode = True)
		#database cursor
		cursor = con.cursor()
		rows = getAllLinks( givenLink, forceRefresh, cursor, site )

		i = 0
		ret = {}
		for row in rows:
			link = row[0]
			pid = row[1]
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
					con.commit()
					return ret
				cursor.execute( "INSERT into deletedProducts(productid) values(%s)",( row[1] ) )
				print str(e)
				print '>>> traceback <<<'
				traceback.print_exc()
				print '>>> end of traceback <<<'
				print "skipping item: " + link
				continue

			ret = insertOrUpdate(item, cursor, uniqueId, site, forceRefresh, pid)
			i+=1
			if ( ( i + 1 ) % 5 == 0):
				if ( i % 20 == 0):
					print str(i) + " done"
				con.commit()
		con.commit()
		return ret

	except _mysql.Error, e:
		print "Error %d: %s" % (e.args[0], e.args[1])
		sys.exit(1)
	finally:
		if con:
			con.commit()
			con.close()
