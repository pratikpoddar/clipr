#!/usr/bin/env python
import ConfigParser, operator, itertools, os, _mysql, sys, logging, __main__, random
import MySQLdb as mdb

from collections import defaultdict
from Queue import PriorityQueue, Full
from optparse import OptionParser
#####################################################
################## Program options ##################
#####################################################

parser.set_defaults(source=0)
parser.set_defaults(uid=-1)

parser.add_option("-u", "--userid", dest="uid",
				  help="id of user for whom to compute the like categories", metavar="USERID")
parser.add_option("-s", "--source", dest="source",
				  help="id of friend whose input should be considered", metavar="SOURCEID")

(options, args) = parser.parse_args()

parsedArgs = parser.parse_args()[0]

USERID = long(parsedArgs.uid)
if long(parsedArgs.source):
	SOURCE = long(parsedArgs.source)
else:
	SOURCE = USERID

###################################################
############### End Program options ###############
###################################################


con = None

###############################################################################################
## returns a constant value (used to provide default weights of tags while decaying them)
###############################################################################################

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../db.ini')
	dictionary = {}
	for section in config.sections():
	    dictionary[section] = {}
	    for option in config.options(section):
	        dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

def updatedRecently(cursor, uid, source):
	cursor.execute('SELECT max(time) from userTopProducts where userid= %s and requester = %s',(uid,source))
	res = cursor.fetchone()
	if res and res[0]:
		lastupdate = datetime.strptime(str(res[0]), '%Y-%m-%d %H:%M:%S')
		if (datetime.now() - lastupdate) < timedelta(hours=1):
			cursor.execute('SELECT max(timestamp) from interestsTable where userid = %s and source = %s',(uid,source))
			res2 = cursor.fetchone()
			if not res2:
				return False
			else:
				if not res2[0]:
					return False
				interestupdatetime = datetime.strptime(str(res2[0]), '%Y-%m-%d %H:%M:%S')
				if interestupdatetime < lastupdate:
					return False
	return True


###################################################
############# Main Logic begins here ##############
###################################################
genderMap = {'male' : 1, 'female': 0, '': -1}
try:
	#stats
	connectionsettings = getdbsettings()
	con = mdb.connect(connectionsettings['server'], connectionsettings['user'], connectionsettings['password'], connectionsettings['db_name'])
	con.autocommit(True)
	cursor = con.cursor()

	if not updatedRecently(cursor, USERID, SOURCE):
		sys.exit(1)

	interestMatchedProductSet = set()
	gender = ''
	cursor.execute("SELECT gender from fbdata where userid = %s", (USERID))
	rows = cursor.fetchall()
	if rows:
		gender = rows[0][0]
	
	############# finding products which match user intersts ##############
	cursor.execute("SELECT interest from interestsTable where userid = %s and source = %s", (USERID, SOURCE))

	userInterests = cursor.fetchall()

	for userInterest in map(lambda x: x[0], userInterests):
		cursor.execute("SELECT ctt.productid from cliprTagTable as ctt join productGender as pg on ctt.productid = pg.productid where ctt.cliprTag = %s and pg.gender != %s",(userInterest, genderMap[gender]))
		productsWithInterest = cursor.fetchall()
		for product in map(lambda x: x[0], productsWithInterest):
			interestMatchedProductSet.add(product)
			prodRelevantTags[product] += TAG_VALUE[userInterest]
	interestMatchedProductRandomList = list(interestMatchedProductSet)
	################# finding products clipped by friends ##################
	cursor.execute("SELECT productid, count(*), max(timestamp) from fbfriend as ff join clipsTable as ct where ff.userid =%s and ff.friendid = ct.userid and ff.follow = 1 group by productid", (USERID))
	friendClipData = cursor.fetchall()

	for row in friendClipData:
		prodFriendClips[row[0]] = row[1] * getTimeDecay(row[2])

	################# finding products clipped by USER ##################
	cursor.execute("SELECT productid, timestamp from clipsTable as ct where ct.userid =%s", (USERID))
	ownClipData = cursor.fetchall()

	for row in ownClipData:
		prodOwnClips[row[0]] = 1 * getTimeDecay(row[1])

	################ finding products clipped by all users #################
	cursor.execute("SELECT productid,count(*),max(timestamp) from  clipsTable group by productid")
	allUsersClipData = cursor.fetchall()

	for row in allUsersClipData:
		prodTotalClips[row[0]] = row[1] * getTimeDecay(row[2])

	################ finding number of tags per each product #################
	cursor.execute("SELECT productid,count(*) from  cliprTagTable group by productid")
	totalTagData = cursor.fetchall()

	for row in totalTagData:
		numTags[row[0]] = row[1]


	finalProdScore2 = list()

	################ scoring the products to get an order for the feed #################
	for product in interestMatchedProductRandomList:
		finalProdScore[product] = 1.0*prodRelevantTags[product]/numTags[product] * INTEREST_WEIGHT + prodFriendClips[product] * FRIEND_CLIP_WEIGHT + prodOwnClips[product] * OWN_CLIP_WEIGHT + prodTotalClips[product] * TOTAL_CLIP_WEIGHT
		finalProdScore2.append( ( product, finalProdScore[product] ) ) 

	orderedProducts = sorted(finalProdScore2, key=lambda k: k[1])
	
	if userInterests:
		resortedProducts = resortProducts( orderedProducts, finalProdScore, cursor )
	else:
		resortedProducts = orderedProducts
	
	cursor.execute("SELECT productid from  productDetail order by productid")
	allProducts = cursor.fetchall()
	prods = []
	for row in allProducts:
		prods.append(row[0])

	random.seed(SEED)
	random.shuffle(prods)
	cursor.execute("DELETE from userTopProducts where userid = %s", (USERID))
	cursor.execute("SET AUTOCOMMIT=0")
	for prod in prods:
		for eachProduct in resortedProducts:
			if eachProduct[0] == prod:
				cursor.execute("INSERT into userTopProducts(userid, productid, score, requester) values (%s,%s,%s,%s)",(USERID, eachProduct[0], finalProdScore[eachProduct[0]], SOURCE))
				break
	if len(resortedProducts) < 100:
		cursor.execute("SELECT productid, count(*) as numClips from clipsTable as ct join fbdata on ct.userid = fbdata.userid where fbdata.gender = %s \
			group by productid order by numClips desc limit %s",(gender, (100 - len(resortedProducts) ) ) )
		products = cursor.fetchall()
		for product in products:
			cursor.execute("INSERT into userTopProducts(userid, productid, score, requester) values (%s,%s,%s,%s)",(USERID, product[0], 0, SOURCE))
	con.commit()
	cursor.execute("SET AUTOCOMMIT=1")

except _mysql.Error, e:
	# logger.error( "%s",str(e), extra=userDict )
	sys.exit(1)
finally:
	if con:
		cursor = con.cursor()
		con.commit()
		con.close()
