#!/usr/bin/env python
import ConfigParser
import sys
from optparse import OptionParser
import os
import _mysql
import sys
import MySQLdb as mdb

currDir = os.path.dirname(os.path.abspath(__file__))

sys.path.insert(0, currDir+'/adventure18/adventure18/spiders')
sys.path.insert(0, currDir+'/afday/afday/spiders')
sys.path.insert(0, currDir+'/bewakoof/bewakoof/spiders')
sys.path.insert(0, currDir+'/bluegape/bluegape/spiders')
sys.path.insert(0, currDir+'/bluestone/bluestone/spiders')
sys.path.insert(0, currDir+'/cbazaar/cbazaar/spiders')
sys.path.insert(0, currDir+'/chemicallocha/chemicallocha/spiders')
sys.path.insert(0, currDir+'/chumbak/chumbak/spiders')
sys.path.insert(0, currDir+'/craftsvilla/craftsvilla/spiders')
sys.path.insert(0, currDir+'/donebynone/donebynone/spiders')
sys.path.insert(0, currDir+'/fabfurnish/fabfurnish/spiders')
sys.path.insert(0, currDir+'/fashionara/fashionara/spiders')
sys.path.insert(0, currDir+'/fetise/fetise/spiders')
sys.path.insert(0, currDir+'/flipkart/flipkart/spiders')
sys.path.insert(0, currDir+'/gloob/gloob/spiders')
sys.path.insert(0, currDir+'/happilyunmarried/happilyunmarried/spiders')
sys.path.insert(0, currDir+'/healthkart/healthkart/spiders')
sys.path.insert(0, currDir+'/hitplay/hitplay/spiders')
sys.path.insert(0, currDir+'/inonit/inonit/spiders')
sys.path.insert(0, currDir+'/itsourstudio/itsourstudio/spiders')
sys.path.insert(0, currDir+'/myntra/myntra/spiders')
sys.path.insert(0, currDir+'/postergully/postergully/spiders')
sys.path.insert(0, currDir+'/rangiru/rangiru/spiders')
sys.path.insert(0, currDir+'/roomstory/roomstory/spiders')
sys.path.insert(0, currDir+'/shaze/shaze/spiders')
sys.path.insert(0, currDir+'/shortcircuit/shortcircuit/spiders')
sys.path.insert(0, currDir+'/styletag/styletag/spiders')
sys.path.insert(0, currDir+'/urbantouch/urbantouch/spiders')
sys.path.insert(0, currDir+'/villcart/villcart/spiders')

import addAdventure18Product
import addAfdayProduct
import addBewakoofProduct
import addBluegapeProduct
import addBluestoneProduct
import addCbazaarProduct
import addChemicallochaProduct
import addChumbakProduct
import addCraftsvillaProduct
import addDonebynoneProduct
import addFabfurnishProduct
import addFashionaraProduct
import addFetiseProduct
import addFlipkartProduct
import addGloobProduct
import addHappilyunmarriedProduct
import addHealthkartProduct
import addHitplayProduct
import addInonitProduct
import addItsourstudioProduct
import addMyntraProduct
import addPostergullyProduct
import addRangiruProduct
import addRoomstoryProduct
import addShazeProduct
import addShortcircuitProduct
import addStyletagProduct
import addUrbantouchProduct
import addVillcartProduct

parser = OptionParser()

parser.set_defaults(linkToParse="")

parser.add_option( "-l", "--link", dest="linkToParse",
				  help="link of the product to be parsed ", metavar="LINK")

parser.add_option( "-u", "--userid", dest="userid",default=0,
				  help="userid who takes this action (default 0 for system commands)", metavar="LINK")

parser.add_option( "-s", "--store", dest="storeToUpdate",default="",
				  help="siteId of the store to be updated(e.g. flipkart)\nThis is ignored if a link is provided ", metavar="STORE")

parser.add_option( "-r", action="store_true", dest="refresh",default=False,
				  help="refresh entire database of products\nThis is ignored if a link or a store is provided ", metavar="REFRESH")

parser.add_option( "-f", action="store_true", dest="force",default=False,
				  help="Force refresh of the databse even if it was fetched recently\nThis is used only when refresh/store option is applied ", metavar="FORCE")

(options, args) = parser.parse_args()

parsedArgs = parser.parse_args()[0]

LINK = str(parsedArgs.linkToParse)

STORE = str(parsedArgs.storeToUpdate)

REFRESH = str(parsedArgs.refresh)

FORCE = str(parsedArgs.force)

USERID = str(parsedArgs.userid)

def toUtf(string):
	return (unicode(string)).encode("utf-8")

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../../db.ini')
	dictionary = {}
	for section in config.sections():
	    dictionary[section] = {}
	    for option in config.options(section):
	        dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

def recordAddProductEvent(userid, result, link):
	con = None
	try:
		connectionsettings = getdbsettings()
		con = mdb.connect(host= connectionsettings['server'], user = connectionsettings['user'], passwd = connectionsettings['password'], db = connectionsettings['db_name'], charset = "utf8", use_unicode = True)
		#database cursor
		cursor = con.cursor()
		if ( not result ):
			cursor.execute("INSERT into addedProduct(userid, productid, link, repeatadd) values(%s,%s,%s,%s)",(str(userid), 0, toUtf(link),0 ))
		else:
			if (result['isNew']):
				cursor.execute("INSERT into addedProduct(userid, productid, link, repeatadd) values(%s,%s,%s,%s)",(str(userid), str(result['productid']), toUtf(link), 0 ))
			else:
				cursor.execute("INSERT into addedProduct(userid, productid, link, repeatadd) values(%s,%s,%s,%s)",(str(userid), str(result['productid']), toUtf(link), 1 ))
	except _mysql.Error, e:
		print 0
		sys.exit(1)
	finally:
		if con:
			con.commit()
			con.close()

# try:
result = {}
if LINK:
	linkTokens = LINK.split('//')
	if len(linkTokens) > 2:
		raise Exception("// occurs twice in link")
	elif len(linkTokens) == 1:
		strippedLink = LINK
		LINK = "http://"+LINK
	else:
		strippedLink = linkTokens[len(linkTokens)-1]
	if strippedLink.startswith('www.adventure18.com') or strippedLink.startswith('adventure18.com'):
		result = addAdventure18Product.parseLink( LINK )
	elif strippedLink.startswith('www.afday.com') or strippedLink.startswith('afday.com'):
		result = addAfdayProduct.parseLink( LINK )
	elif strippedLink.startswith('www.bewakoof.com') or strippedLink.startswith('bewakoof.com'):
		result = addBewakoofProduct.parseLink( LINK )
	elif strippedLink.startswith('www.bluegape.com') or strippedLink.startswith('bluegape.com'):
		result = addBluegapeProduct.parseLink( LINK )
	elif strippedLink.startswith('www.bluestone.com') or strippedLink.startswith('bluestone.com'):
		result = addBluestoneProduct.parseLink( LINK )
	elif strippedLink.startswith('www.cbazaar.in') or strippedLink.startswith('cbazaar.in'):
		result = addCbazaarProduct.parseLink( LINK )
	elif strippedLink.startswith('www.thechemicallocha.com') or strippedLink.startswith('thechemicallocha.com'):
		result = addChemicallochaProduct.parseLink( LINK )
	elif strippedLink.startswith('www.chumbak.com') or strippedLink.startswith('chumbak.com'):
		result = addChumbakProduct.parseLink( LINK )
	elif strippedLink.startswith('www.craftsvilla.com') or strippedLink.startswith('craftsvilla.com'):
		result = addCraftsvillaProduct.parseLink( LINK )
	elif strippedLink.startswith('www.donebynone.com') or strippedLink.startswith('donebynone.com'):
		result = addDonebynoneProduct.parseLink( LINK )
	elif strippedLink.startswith('www.fabfurnish.com') or strippedLink.startswith('fabfurnish.com'):
		result = addFabfurnishProduct.parseLink( LINK )
	elif strippedLink.startswith('www.fashionara.com') or strippedLink.startswith('fashionara.com'):
		result = addFashionaraProduct.parseLink( LINK )
	elif strippedLink.startswith('www.fetise.com') or strippedLink.startswith('fetise.com'):
		result = addFetiseProduct.parseLink( LINK )
	elif strippedLink.startswith('www.flipkart.com') or strippedLink.startswith('flipkart.com'):
		result = addFlipkartProduct.parseLink( LINK )
	elif strippedLink.startswith('www.gloob.in') or strippedLink.startswith('gloob.in'):
		result = addGloobProduct.parseLink( LINK )
	elif strippedLink.startswith('www.happilyunmarried.com') or strippedLink.startswith('happilyunmarried.com'):
		result = addHappilyunmarriedProduct.parseLink( LINK )
	elif strippedLink.startswith('www.healthkart.com') or strippedLink.startswith('healthkart.com'):
		result = addHealthkartProduct.parseLink( LINK )
	elif strippedLink.startswith('www.hitplay.in') or strippedLink.startswith('hitplay.in'):
		result = addHitplayProduct.parseLink( LINK )
	elif strippedLink.startswith('www.inonit.in') or strippedLink.startswith('www.shop.inonit.in'):
		result = addInonitProduct.parseLink( LINK )
	elif strippedLink.startswith('www.itsourstudio.com') or strippedLink.startswith('itsourstudio.com'):
		result = addItsourstudioProduct.parseLink( LINK )
	elif strippedLink.startswith('www.myntra.com') or strippedLink.startswith('myntra.com'):
		result = addMyntraProduct.parseLink( LINK )
	elif strippedLink.startswith('www.postergully.com') or strippedLink.startswith('postergully.com'):
		result = addPostergullyProduct.parseLink( LINK )
	elif strippedLink.startswith('www.rangiru.com') or strippedLink.startswith('rangiru.com'):
		result = addRangiruProduct.parseLink( LINK )
	elif strippedLink.startswith('www.roomstory.com') or strippedLink.startswith('roomstory.com'):
		result = addRoomstoryProduct.parseLink( LINK )
	elif strippedLink.startswith('www.shaze.in') or strippedLink.startswith('shaze.in'):
		result = addShazeProduct.parseLink( LINK )
	elif strippedLink.startswith('www.shortcircuit.in') or strippedLink.startswith('shortcircuit.in') or strippedLink.startswith('www.shop.shortcircuit.in') or strippedLink.startswith('shop.shortcircuit.in'):
		result = addShortcircuitProduct.parseLink( LINK )
	elif strippedLink.startswith('www.styletag.com') or strippedLink.startswith('styletag.com'):
		result = addStyletagProduct.parseLink( LINK )
	elif strippedLink.startswith('www.urbantouch.com')  or strippedLink.startswith('urbantouch.com'):
		result = addUrbantouchProduct.parseLink( LINK )
	elif strippedLink.startswith('www.villcart.com') or strippedLink.startswith('villcart.com'):
		result = addVillcartProduct.parseLink( LINK )
	else:
		pass
	recordAddProductEvent(USERID, result, LINK)
	if (result):
		print result['productid']
	else:
		print 0
elif STORE:
	if STORE == 'adventure18':
		addAdventure18Product.parseLink( forceRefresh=FORCE )
	elif STORE == 'afday':
		addAfdayProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'bewakoof':
		addBewakoofProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'bluegape':
		addBluegapeProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'bluestone':
		addBluestoneProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'cbazaar':
		addCbazaarProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'chemicallocha':
		addChemicallochaProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'chumbak':
		addChumbakProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'craftsvilla':
		addCraftsvillaProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'donebynone':
		addDonebynoneProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'fabfurnish':
		addFabfurnishProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'fashionara':
		addFashionaraProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'fetise':
		addFetiseProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'flipkart':
		addFlipkartProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'gloob':
		addGloobProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'happilyunmarried':
		addHappilyunmarriedProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'healthkart':
		addHealthkartProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'hitplay':
		addHitplayProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'inonit':
		addInonitProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'itsourstudio':
		addItsourstudioProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'myntra':
		addMyntraProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'postergully':
		addPostergullyProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'rangiru':
		addRangiruProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'roomstory':
		addRoomstoryProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'shaze':
		addShazeProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'shortcircuit':
		addShortcircuitProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'styletag':
		addStyletagProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'urbantouch':
		addUrbantouchProduct.parseLink( forceRefresh=FORCE )
	elif STORE == 'villcart':
		addVillcartProduct.parseLink( forceRefresh=FORCE )
	else:
		print "oops"
elif REFRESH:
	addAdventure18Product.parseLink( forceRefresh=FORCE )
	addAfdayProduct.parseLink( forceRefresh=FORCE )
	addBewakoofProduct.parseLink( forceRefresh=FORCE )
	addBluegapeProduct.parseLink( forceRefresh=FORCE )
	addBluestoneProduct.parseLink( forceRefresh=FORCE )
	addCbazaarProduct.parseLink( forceRefresh=FORCE )
	addChemicallochaProduct.parseLink( forceRefresh=FORCE )
	addChumbakProduct.parseLink( forceRefresh=FORCE )
	addCraftsvillaProduct.parseLink( forceRefresh=FORCE )
	addDonebynoneProduct.parseLink( forceRefresh=FORCE )
	addFabfurnishProduct.parseLink( forceRefresh=FORCE )
	addFashionaraProduct.parseLink( forceRefresh=FORCE )
	addFetiseProduct.parseLink( forceRefresh=FORCE )
	addFlipkartProduct.parseLink( forceRefresh=FORCE )
	addGloobProduct.parseLink( forceRefresh=FORCE )
	addHappilyunmarriedProduct.parseLink( forceRefresh=FORCE )
	addHealthkartProduct.parseLink( forceRefresh=FORCE )
	addHitplayProduct.parseLink( forceRefresh=FORCE )
	addInonitProduct.parseLink( forceRefresh=FORCE )
	addItsourstudioProduct.parseLink( forceRefresh=FORCE )
	addMyntraProduct.parseLink( forceRefresh=FORCE )
	addPostergullyProduct.parseLink( forceRefresh=FORCE )
	addRangiruProduct.parseLink( forceRefresh=FORCE )
	addRoomstoryProduct.parseLink( forceRefresh=FORCE )
	addShazeProduct.parseLink( forceRefresh=FORCE )
	addShortcircuitProduct.parseLink( forceRefresh=FORCE )
	addStyletagProduct.parseLink( forceRefresh=FORCE )
	addUrbantouchProduct.parseLink( forceRefresh=FORCE )
	addVillcartProduct.parseLink( forceRefresh=FORCE )

else:
	print "No option(link/store/refresh) is provided "
	exit(0)

