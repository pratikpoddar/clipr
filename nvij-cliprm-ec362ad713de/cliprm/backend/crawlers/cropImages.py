#!/usr/bin/env python
import ConfigParser
import PIL
import hashlib
from PIL import Image
import _mysql
import sys
import MySQLdb as mdb

BIG_WIDTH = 360

config = ConfigParser.ConfigParser()
config.read('../../db.ini')
dictionary = {}
for section in config.sections():
    dictionary[section] = {}
    for option in config.options(section):
        dictionary[section][option] = config.get(section, option)

BASEFOLDER = dictionary['images']['folder']

def validAspect(image):
	# finding hashed file name
	filename = hashlib.md5( image ).hexdigest()

	# filename for both image sizes
	bigImageFile = BASEFOLDER + str(filename) + '-big.jpg'

	# Store image for prodList page
	img = Image.open(bigImageFile)
	aspect = float(img.size[0])/img.size[1]
	return ( aspect < 1.6 and aspect > 0.625 )

def setBadImageQuality(cursor, pid):
	print pid
	cursor.execute("UPDATE productDetail set imageQuality = 0 where productid = %s",(str(pid)) )


def cropBigImage(image):
	# finding hashed file name
	filename = hashlib.md5( image ).hexdigest()

	# filename for both image sizes
	bigImageFile = BASEFOLDER + str(filename) + '-orig.jpg'
	# Store image for prodList page
	cropImageFile = BASEFOLDER + str(filename) + '-crop.jpg'
	# Store image for mobile/tablet page
	thumbImageFile = BASEFOLDER + str(filename) + '-thumb.jpg'
	# Store tiny image for small thumbnails
	tinyImageFile = BASEFOLDER + str(filename) + '-tiny.jpg'

	img = Image.open(bigImageFile)

	width = img.size[0]
	height = img.size[1]
	if width > height:
		diff = width-height
		img = img.crop((diff/2, 0, (width-diff/2), height))
	else:
		diff = height-width
		img = img.crop((0, diff/2, width, (height-diff/2)))
	img.thumbnail((360,360),PIL.Image.ANTIALIAS)
	if img.mode != "RGB":
		img = img.convert("RGB")
	# Writing image to resized file
	img.save(cropImageFile)

	img.thumbnail((240,240),PIL.Image.ANTIALIAS)
	if img.mode != "RGB":
		img = img.convert("RGB")
	# Writing image to resized file
	img.save(thumbImageFile)

	img.thumbnail((60,60),PIL.Image.ANTIALIAS)
	if img.mode != "RGB":
		img = img.convert("RGB")
	img.save(tinyImageFile)

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../../db.ini')
	dictionary = {}
	for section in config.sections():
		dictionary[section] = {}
		for option in config.options(section):
			dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

con = None
try:
	connectionsettings = getdbsettings()
	con = mdb.connect(host=connectionsettings['server'] ,user=connectionsettings['user'] ,passwd=connectionsettings['password'],db=connectionsettings['db_name'], charset = "utf8", use_unicode = True)
	#database cursor
	cursor = con.cursor()
	cursor.execute( "SELECT image,productid from productDetail order by productid" )
	rows = cursor.fetchall()

	i = 0
	ret = {}
	for row in rows:
		images = row[0].split('$$$')
		image = images[0]
		try:
			if( not validAspect(image)):
				setBadImageQuality(cursor, row[1])
			cropBigImage(image)
		except Exception, e:
			setBadImageQuality(cursor, row[1])
			continue
	con.commit()
except _mysql.Error, e:
	sys.exit(1)
finally:
	if con:
		con.commit()
		con.close()
