import _mysql
import sys
import MySQLdb as mdb
from imageWriter import writeImages

try:
	#stats
	con = mdb.connect('localhost', 'root', '12345678', 'clipr')
	con.autocommit(True)

	cursor = con.cursor()

	cursor.execute("SELECT productid, image from productDetail")

	prodImages = cursor.fetchall()

	for prodImage in prodImages:
		prodId = prodImage[0]
		images = prodImage[1]
		if images:
			imageList = images.split('$$$')
			for image in imageList:
				try:
					writeImages(image)
				except Exception, e:
					continue

except _mysql.Error, e:
	logger.error( "%s",str(e), extra=userDict )
	sys.exit(1)
finally:
	if con:
		cursor = con.cursor()
		con.commit()
		con.close()
