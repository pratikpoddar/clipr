from mobile.models import Addedproduct, Productdetail, CliprUser
import sys
import os

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

def refreshStore(store,forceRefresh):
	if STORE == 'adventure18':
		addAdventure18Product.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'afday':
		addAfdayProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'bewakoof':
		addBewakoofProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'bluegape':
		addBluegapeProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'bluestone':
		addBluestoneProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'cbazaar':
		addCbazaarProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'chemicallocha':
		addChemicallochaProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'chumbak':
		addChumbakProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'craftsvilla':
		addCraftsvillaProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'donebynone':
		addDonebynoneProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'fabfurnish':
		addFabfurnishProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'fashionara':
		addFashionaraProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'fetise':
		addFetiseProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'flipkart':
		addFlipkartProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'gloob':
		addGloobProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'happilyunmarried':
		addHappilyunmarriedProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'healthkart':
		addHealthkartProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'hitplay':
		addHitplayProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'inonit':
		addInonitProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'itsourstudio':
		addItsourstudioProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'myntra':
		addMyntraProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'postergully':
		addPostergullyProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'rangiru':
		addRangiruProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'roomstory':
		addRoomstoryProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'shaze':
		addShazeProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'shortcircuit':
		addShortcircuitProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'styletag':
		addStyletagProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'urbantouch':
		addUrbantouchProduct.parseLink( forceRefresh=forceRefresh )
	elif STORE == 'villcart':
		addVillcartProduct.parseLink( forceRefresh=forceRefresh )
	else:
		print "oops"

def refreshAll(forceRefresh):
	addAdventure18Product.parseLink( forceRefresh=forceRefresh )
	addAfdayProduct.parseLink( forceRefresh=forceRefresh )
	addBewakoofProduct.parseLink( forceRefresh=forceRefresh )
	addBluegapeProduct.parseLink( forceRefresh=forceRefresh )
	addBluestoneProduct.parseLink( forceRefresh=forceRefresh )
	addCbazaarProduct.parseLink( forceRefresh=forceRefresh )
	addChemicallochaProduct.parseLink( forceRefresh=forceRefresh )
	addChumbakProduct.parseLink( forceRefresh=forceRefresh )
	addCraftsvillaProduct.parseLink( forceRefresh=forceRefresh )
	addDonebynoneProduct.parseLink( forceRefresh=forceRefresh )
	addFabfurnishProduct.parseLink( forceRefresh=forceRefresh )
	addFashionaraProduct.parseLink( forceRefresh=forceRefresh )
	addFetiseProduct.parseLink( forceRefresh=forceRefresh )
	addFlipkartProduct.parseLink( forceRefresh=forceRefresh )
	addGloobProduct.parseLink( forceRefresh=forceRefresh )
	addHappilyunmarriedProduct.parseLink( forceRefresh=forceRefresh )
	addHealthkartProduct.parseLink( forceRefresh=forceRefresh )
	addHitplayProduct.parseLink( forceRefresh=forceRefresh )
	addInonitProduct.parseLink( forceRefresh=forceRefresh )
	addItsourstudioProduct.parseLink( forceRefresh=forceRefresh )
	addMyntraProduct.parseLink( forceRefresh=forceRefresh )
	addPostergullyProduct.parseLink( forceRefresh=forceRefresh )
	addRangiruProduct.parseLink( forceRefresh=forceRefresh )
	addRoomstoryProduct.parseLink( forceRefresh=forceRefresh )
	addShazeProduct.parseLink( forceRefresh=forceRefresh )
	addShortcircuitProduct.parseLink( forceRefresh=forceRefresh )
	addStyletagProduct.parseLink( forceRefresh=forceRefresh )
	addUrbantouchProduct.parseLink( forceRefresh=forceRefresh )
	addVillcartProduct.parseLink( forceRefresh=forceRefresh )

def addSingleProduct(url,uid):
	linkTokens = url.split('//')
	if len(linkTokens) > 2:
		raise Exception("// occurs twice in link")
	elif len(linkTokens) == 1:
		strippedLink = url
		url = "http://"+url
	else:
		strippedLink = linkTokens[len(linkTokens)-1]
	if strippedLink.startswith('www.adventure18.com') or strippedLink.startswith('adventure18.com'):
		result = addAdventure18Product.parseLink( url )
	elif strippedLink.startswith('www.afday.com') or strippedLink.startswith('afday.com'):
		result = addAfdayProduct.parseLink( url )
	elif strippedLink.startswith('www.bewakoof.com') or strippedLink.startswith('bewakoof.com'):
		result = addBewakoofProduct.parseLink( url )
	elif strippedLink.startswith('www.bluegape.com') or strippedLink.startswith('bluegape.com'):
		result = addBluegapeProduct.parseLink( url )
	elif strippedLink.startswith('www.bluestone.com') or strippedLink.startswith('bluestone.com'):
		result = addBluestoneProduct.parseLink( url )
	elif strippedLink.startswith('www.cbazaar.in') or strippedLink.startswith('cbazaar.in'):
		result = addCbazaarProduct.parseLink( url )
	elif strippedLink.startswith('www.thechemicallocha.com') or strippedLink.startswith('thechemicallocha.com'):
		result = addChemicallochaProduct.parseLink( url )
	elif strippedLink.startswith('www.chumbak.com') or strippedLink.startswith('chumbak.com'):
		result = addChumbakProduct.parseLink( url )
	elif strippedLink.startswith('www.craftsvilla.com') or strippedLink.startswith('craftsvilla.com'):
		result = addCraftsvillaProduct.parseLink( url )
	elif strippedLink.startswith('www.donebynone.com') or strippedLink.startswith('donebynone.com'):
		result = addDonebynoneProduct.parseLink( url )
	elif strippedLink.startswith('www.fabfurnish.com') or strippedLink.startswith('fabfurnish.com'):
		result = addFabfurnishProduct.parseLink( url )
	elif strippedLink.startswith('www.fashionara.com') or strippedLink.startswith('fashionara.com'):
		result = addFashionaraProduct.parseLink( url )
	elif strippedLink.startswith('www.fetise.com') or strippedLink.startswith('fetise.com'):
		result = addFetiseProduct.parseLink( url )
	elif strippedLink.startswith('www.flipkart.com') or strippedLink.startswith('flipkart.com'):
		result = addFlipkartProduct.parseLink( url )
	elif strippedLink.startswith('www.gloob.in') or strippedLink.startswith('gloob.in'):
		result = addGloobProduct.parseLink( url )
	elif strippedLink.startswith('www.happilyunmarried.com') or strippedLink.startswith('happilyunmarried.com'):
		result = addHappilyunmarriedProduct.parseLink( url )
	elif strippedLink.startswith('www.healthkart.com') or strippedLink.startswith('healthkart.com'):
		result = addHealthkartProduct.parseLink( url )
	elif strippedLink.startswith('www.hitplay.in') or strippedLink.startswith('hitplay.in'):
		result = addHitplayProduct.parseLink( url )
	elif strippedLink.startswith('www.inonit.in') or strippedLink.startswith('www.shop.inonit.in'):
		result = addInonitProduct.parseLink( url )
	elif strippedLink.startswith('www.itsourstudio.com') or strippedLink.startswith('itsourstudio.com'):
		result = addItsourstudioProduct.parseLink( url )
	elif strippedLink.startswith('www.myntra.com') or strippedLink.startswith('myntra.com'):
		result = addMyntraProduct.parseLink( url )
	elif strippedLink.startswith('www.postergully.com') or strippedLink.startswith('postergully.com'):
		result = addPostergullyProduct.parseLink( url )
	elif strippedLink.startswith('www.rangiru.com') or strippedLink.startswith('rangiru.com'):
		result = addRangiruProduct.parseLink( url )
	elif strippedLink.startswith('www.roomstory.com') or strippedLink.startswith('roomstory.com'):
		result = addRoomstoryProduct.parseLink( url )
	elif strippedLink.startswith('www.shaze.in') or strippedLink.startswith('shaze.in'):
		result = addShazeProduct.parseLink( url )
	elif strippedLink.startswith('www.shortcircuit.in') or strippedLink.startswith('shortcircuit.in') or strippedLink.startswith('www.shop.shortcircuit.in') or strippedLink.startswith('shop.shortcircuit.in'):
		result = addShortcircuitProduct.parseLink( url )
	elif strippedLink.startswith('www.styletag.com') or strippedLink.startswith('styletag.com'):
		result = addStyletagProduct.parseLink( url )
	elif strippedLink.startswith('www.urbantouch.com')  or strippedLink.startswith('urbantouch.com'):
		result = addUrbantouchProduct.parseLink( url )
	elif strippedLink.startswith('www.villcart.com') or strippedLink.startswith('villcart.com'):
		result = addVillcartProduct.parseLink( url )
	else:
		pass
	recordAddProductEvent(uid, result, url)
	if (result):
		return result['productid']
	else:
		return 0
def toUtf(string):
	return (unicode(string)).encode("utf-8")

def recordAddProductEvent(userid, result, link):
	if result:
		if (result['isNew']):
			new_entry=Addedproduct(user=CliprUser.objects.get(userid=userid), product=Productdetail.objects.get(productid=result['productid']), link=link, repeatadd=0)
			new_entry.save()
		else:
			new_entry=Addedproduct(user=CliprUser.objects.get(userid=userid), product=Productdetail.objects.get(productid=result['productid']), link=link, repeatadd=1)
			new_entry.save()
