#!/usr/bin/env python
import ConfigParser, operator, itertools, os, _mysql, sys, logging, __main__, random, pytz
import MySQLdb as mdb

from collections import defaultdict
from math import log, sqrt, exp
from Queue import PriorityQueue, Full
from optparse import OptionParser
from time import strftime, time
from datetime import datetime, date, timedelta
from pytz import timezone
#####################################################
################## Program options ##################
#####################################################

parser = OptionParser()

parser.set_defaults(upperlimit=100000000)
parser.set_defaults(lowerlimit=0)
parser.set_defaults(source=0)
parser.set_defaults(uid=-1)
parser.set_defaults(pid=0)

parser.add_option("-u", "--userid", dest="uid",
				  help="id of user for whom to compute the like categories", metavar="USERID")
parser.add_option("-p", "--productid", dest="pid",
				  help="id of product to get top similar products", metavar="PRODUCTID")
parser.add_option("-s", "--source", dest="source",
				  help="id of friend whose input should be considered", metavar="SOURCEID")
parser.add_option("-l", "--upperlimit", dest="upperlimit",
				  help="upper limit of price of products", metavar="UPPER_LIMIT")
parser.add_option("-g", "--lowerlimit", dest="lowerlimit",
				  help="lower limit of price of products", metavar="LOWER_LIMIT")
parser.add_option( "-f", action="store_true", dest="force",default=False,
				  help="Force recalculation of userTopProducts ", metavar="FORCE")

(options, args) = parser.parse_args()

parsedArgs = parser.parse_args()[0]

USERID = long(parsedArgs.uid)
PRODUCTID = long(parsedArgs.pid)
if long(parsedArgs.source):
	SOURCE = long(parsedArgs.source)
else:
	SOURCE = USERID
FORCEUPDATE = parsedArgs.force
LOWER_LIMIT = long(parsedArgs.lowerlimit)
UPPER_LIMIT = long(parsedArgs.upperlimit)

SEED = int(datetime.today().date().strftime('%Y%m%d'))
###################################################
############### End Program options ###############
###################################################

# TODO: change this to ../../logFiles 

LOG_DIR_PATH = '../logFiles/'

fileName = (__main__.__file__).split('.py')[0]

# logger = logging.getLogger( fileName )

# logger.setLevel(logging.DEBUG)

logFileName = LOG_DIR_PATH + fileName + str(date.today())

# fhDeb = logging.FileHandler(logFileName + "_debug.log")
# fhDeb.setLevel(logging.DEBUG)

# fhInfo = logging.FileHandler(logFileName + "_info.log")
# fhInfo.setLevel(logging.INFO)

ch = logging.StreamHandler()
ch.setLevel(logging.INFO)

formatter = logging.Formatter('%(asctime)s - %(name)s - %(userid)s - %(levelname)s - %(message)s')

ch.setFormatter(formatter)
# fhDeb.setFormatter(formatter)
# fhInfo.setFormatter(formatter)

# logger.addHandler(ch)
# logger.addHandler(fhDeb)
# logger.addHandler(fhInfo)
userDict = {'userid':str(USERID)}

# logger.info( "passed arguments: %s",parser.parse_args()[0], extra = userDict) 


INTEREST_WEIGHT		= 1.0
TOTAL_CLIP_WEIGHT   = 0.1
if SOURCE == USERID:
	FRIEND_CLIP_WEIGHT	= 0.4
else:
	FRIEND_CLIP_WEIGHT	= 0

if SOURCE == USERID:
	OWN_CLIP_WEIGHT	= 0.0
else:
	OWN_CLIP_WEIGHT	= 2.0

DECAY_FACTOR =0.15

con = None

###############################################################################################
## returns a constant value (used to provide default weights of tags while decaying them)
###############################################################################################
def defaultValue(value):
    return itertools.repeat(value).next
TAG_VALUE = defaultdict( defaultValue(1) )
TAG_VALUE[25] = 3 #Fashion
TAG_VALUE[47] = 2 #photography

def getTimeDecay(t0):
	currTime = datetime.now(pytz.utc)
	actionTime = datetime.strptime(str(t0), '%Y-%m-%d %H:%M:%S')
	# make it timezone aware to satisfy python compiler
	awareActionTime = actionTime.replace(tzinfo=pytz.UTC)
	elapsedMin = ((currTime - awareActionTime).seconds)/60.0
	halflife = 12 * 60 # 12 hours halflife
	tau = 1.44*halflife
	return exp(-elapsedMin/tau)

def resortProducts( orderedProducts, prodScore, cursor ):
	global numTags
	cursor.execute("SELECT interest from interestsTable where userid = %s and source = %s", (USERID, SOURCE))
	userInterestString = reduce( lambda x, y: str(x) + "," + str(y), map(lambda x: x[0], cursor.fetchall()) )

	tagDecayScore = defaultdict( defaultValue(1.0) )
	newProdScore = list()
	for product in map (lambda x: x[0], orderedProducts):
		query = "SELECT cliprTag from cliprTagTable where productid = " + str(product) + " and cliprTag in (" + userInterestString + ")"
		cursor.execute(query)
		tagsForProduct = map( lambda x: x[0], cursor.fetchall() )
		for tag in tagsForProduct:
			prodScore[ product ] = prodScore[ product ] - ( 1.0 - tagDecayScore[ tag ] )/numTags[ product ]
			tagDecayScore[ tag ] = tagDecayScore[tag]* (1 - DECAY_FACTOR)
		newProdScore.append( ( product, prodScore[ product ] ) ) 
	resortedProds = sorted(newProdScore, key=lambda k: k[1])
	return resortedProds

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
				return True
			else:
				if not res2[0]:
					return True
				interestupdatetime = datetime.strptime(str(res2[0]), '%Y-%m-%d %H:%M:%S')
				if interestupdatetime < lastupdate:
					return True
	return False


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
	if USERID>=0:
		if not( FORCEUPDATE ) and updatedRecently(cursor, USERID, SOURCE):
			sys.exit(1)
		prodRelevantTags = defaultdict(int)
		prodFriendClips = defaultdict(int)
		prodOwnClips = defaultdict(int)
		prodTotalClips = defaultdict(int)
		finalProdScore = defaultdict(float)
		numTags = defaultdict(int)

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

		####################### printing out some debug information #######################
		# for product in prodRelevantTags:
		# 	logger.debug( "product %s \t\t number of relevant tags: %s",str(product), prodRelevantTags[product], extra=userDict )

		# for product in prodFriendClips:
		# 	logger.debug( "product %s \t\t number of friend clips: %s",str(product), prodFriendClips[product], extra=userDict )

		# for product in prodOwnClips:
		# 	logger.debug( "product %s \t\t number of friend clips: %s",str(product), prodOwnClips[product], extra=userDict )

		# for product in prodTotalClips:
		# 	logger.debug( "product %s \t\t total number of  clips: %s",str(product), prodTotalClips[product], extra=userDict )

		# for product in finalProdScore:
		# 	logger.debug( "product %s \t\t final score: %s",str(product), finalProdScore[product], extra=userDict )

	elif PRODUCTID:
	 	RELEVANT_TAGS_WEIGHT = 1
		SIMILAR_PRICE_WEIGHT = 0
		SAME_GROUP_WEIGHT = 3
		SAME_SITE_WEIGHT = 1

		prodRelevantTags = defaultdict(int)
		prodSimilarPrice = defaultdict(int)
		prodSameGroup = defaultdict(int)
		prodSameSite = defaultdict(int)
		prodFinalScore = defaultdict(int)
		allProductSet = set()
		sameTagSet = set()

		cursor.execute("SELECT gender from productGender where productid = %s", (PRODUCTID))
		genderRow = cursor.fetchall()
		gender = -1
		if genderRow:
			# Clever hack: exclude male product recommendations for female products and vice versa, in case of unisex products exclude nothing
			gender = 1 - genderRow[0][0]
		cursor.execute("SELECT siteId,price from productDetail where productid = %s", (PRODUCTID))
		prodinfo = cursor.fetchall()[0]
		site = prodinfo[0]
		price = int(prodinfo[1])
		
		cursor.execute("SELECT groupid from productGroup where productid = %s", (PRODUCTID))
		groupRow = cursor.fetchall()
		group = 0
		if groupRow:
			group = int(groupRow[0][0])

		############# finding products which match user intersts ##############
		cursor.execute("SELECT cliprTag from cliprTagTable where productid = %s", (PRODUCTID))
		productTags = cursor.fetchall()

		firstProd = 1
		for productTag in map(lambda x: x[0], productTags):
			cursor.execute("SELECT ctt.productid from cliprTagTable as ctt left outer join productGender as pg on ctt.productid = pg.productid where ctt.cliprTag = %s and pg.gender != %s",(productTag, gender))
			productsWithTags = cursor.fetchall()
			for product in map(lambda x: x[0], productsWithTags):
				firstProd = 0
				allProductSet.add(product)
				sameTagSet.add(product)
				prodRelevantTags[product] += 1

		if firstProd and not group:
			sys.exit()

		if group:
			cursor.execute("SELECT pg.productid from productGroup as pg left outer join productGender on pg.productid = productGender.productid where pg.groupid = %s and productGender.gender != %s",(group, gender))
			productsWithinGroup = cursor.fetchall()
			for product in map(lambda x: x[0], productsWithinGroup):
				allProductSet.add(product)
				prodSameGroup[product] = 1

		cursor.execute("SELECT pd.productid from productDetail as pd left outer join productGender as pg on pd.productid = pg.productid where pd.siteId = %s and pg.gender != %s",(site, gender))
		productsFromSameSite = cursor.fetchall()
		for product in map(lambda x: x[0], productsFromSameSite):
			allProductSet.add(product)
			prodSameSite[product] = 1

		cursor.execute("SELECT pd.productid from productDetail as pd left outer join productGender as pg on pd.productid = pg.productid where pd.price < %s and pd.price > %s and pg.gender != %s",( 1.4*price, 0.6*price, gender))
		productsWithSimilarPrice = cursor.fetchall()
		for product in map(lambda x: x[0], productsWithSimilarPrice):
			allProductSet.add(product)
			prodSimilarPrice[product] = 1
		
		if len(sameTagSet) > 50:
			productSet = sameTagSet
		else:
			productSet = allProductSet

		for product in productSet:
			prodFinalScore[product] = 	prodRelevantTags[product] * RELEVANT_TAGS_WEIGHT + \
										prodSimilarPrice[product] * SIMILAR_PRICE_WEIGHT + \
										prodSameGroup[product] * SAME_GROUP_WEIGHT + \
										prodSameSite[product] * SAME_SITE_WEIGHT 
		finalProds = sorted(prodFinalScore.iteritems(), key=operator.itemgetter(1))
		finalProds = finalProds[-7:]
		con.autocommit(False)
		cursor.execute("DELETE from productTopSimilar where productid = %s",(PRODUCTID))
		for prod in finalProds:
			cursor.execute("INSERT into productTopSimilar(productid, similar, score) values (%s,%s,%s)",(PRODUCTID, prod[0], prod[1]))
		con.autocommit(True)
except _mysql.Error, e:
	# logger.error( "%s",str(e), extra=userDict )
	sys.exit(1)
finally:
	if con:
		cursor = con.cursor()
		con.commit()
		con.close()
