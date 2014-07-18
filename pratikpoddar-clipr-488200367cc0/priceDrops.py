import ConfigParser
import json
import _mysql
import sys
import MySQLdb as mdb
from time import strftime
from datetime import datetime, date, timedelta

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../db.ini')
	dictionary = {}
	for section in config.sections():
		dictionary[section] = {}
		for option in config.options(section):
			dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

def getMaxDropIndex(history):
	# in reverse order
	history.reverse()
	# latest price
	latestPrice = history[0][1]
	# current date
	currDate = datetime.today().date()
	maxprice = history[0][1]
	maxidx = 0
	currIdx = 0
	for pricePoint in history:
		if pricePoint[1] > maxprice and pricePoint[1] > latestPrice :
			maxidx = currIdx
			maxprice = pricePoint[1]
		currIdx+=1
	return maxidx

def processOutput(cursor,pq):
	for product in pq:
		cursor.execute("INSERT into topDrops(productid, pricedrop,numdays,oldprice) values(%s,%s,%s,%s) ",(str(product[1]),str(product[0]),str(product[2]),str(product[3])))

con = None


try:
	connectionsettings = getdbsettings()
	con = mdb.connect(host=connectionsettings['server'] ,user=connectionsettings['user'] ,passwd=connectionsettings['password'],db=connectionsettings['db_name'], charset = "utf8", use_unicode = True)
	cursor = con.cursor()
	cursor.execute("SELECT * from prodPriceHistory where productid not in (select productid from deletedProducts) and productid in (select productid from productDetail where siteId != 'craftsvilla')")
	products = cursor.fetchall()
	pq = list()
	for product in products:
		productid = product[0]
		history = json.loads(product[1])
		# date from string => object
		history = map ( lambda x: ( datetime.strptime( x[0], '%Y-%m-%d' ).date(), x[1] ) ,  history)
		historyCopy = list(history)
		idx = getMaxDropIndex(historyCopy)
		dropPercent = 100.0*( history[len(history)-1-idx][1] - history[len(history)-1][1]) / history[len(history)-1-idx][1]
		if(idx != 0 and dropPercent<88):
			days = (history[len(history)-1][0] - history[len(history)-1-idx][0]).days
			pq.append((dropPercent,productid,days,history[len(history)-1-idx][1]))
	pq.sort(key=lambda x: -x[0])
	processOutput(cursor,pq)
except _mysql.Error, e:
	logger.error( str(e) )
	sys.exit(1)
finally:
	if con:
		con.commit()
		con.close()


