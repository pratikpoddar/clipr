import json
import _mysql
import sys
import MySQLdb as mdb
from time import strftime
from datetime import datetime, date, timedelta

def appendOrAdd(productid, price, dt, cursor):
	cursor.execute("""SELECT productid, history FROM prodPriceHistory where productid = %s """,(productid))
	updateDate = datetime.strptime(dt, '%Y-%m-%d %H:%M:%S').date()
	res = cursor.fetchone()
	execute = True
	if res:
		if res[1]:
			priceHistory = json.loads(res[1])
			
			# change dates to date objects from string
			priceHistory = map ( lambda x: ( datetime.strptime( x[0], '%Y-%m-%d' ).date(), x[1] ) , priceHistory )
			
			# append todays price if not already present
			if priceHistory[ len( priceHistory ) - 1 ][ 0 ] < updateDate:
				priceHistory.append( ( updateDate, price ) )
			else:
				execute = False
		else:
			priceHistory = [ ( updateDate, price ) ]
	else:
		priceHistory = [ ( updateDate, price ) ]
	priceHistory.sort()

	# change dates back to string
	priceHistorySerializable = map ( lambda x: ( str( x[0] ), x[1] ), priceHistory )
	priceHistorySerialized = json.dumps(priceHistorySerializable)
	if execute:
		print priceHistorySerialized
		cursor.execute("INSERT into prodPriceHistory (productid, history) values(%s,%s) on duplicate key update history = %s",(str(productid),str(priceHistorySerialized),str(priceHistorySerialized)))
con = None

try:
	con = mdb.connect(host='localhost' ,user='root' ,passwd='12345678',db='clipr', charset = "utf8", use_unicode = True)
	cursor = con.cursor()
	cursor.execute("""SELECT productid, price, timestamp FROM productDetail """)
	products = cursor.fetchall()
	i = 0
	for product in products:
		productid = product[0]
		price = product[1]
		timestamp = str(product[2])
		appendOrAdd(productid, price, timestamp, cursor)
		i += 1
		if ( i % 50 == 0 ):
			con.commit()
	con.commit()

except _mysql.Error, e:
	logger.error( str(e) )
	sys.exit(1)
finally:
	if con:
		con.commit()
		con.close()

