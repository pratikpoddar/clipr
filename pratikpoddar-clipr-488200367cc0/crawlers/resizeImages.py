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

def resizeBigImage(image):
	# finding hashed file name
	filename = hashlib.md5( image ).hexdigest()

	# filename for both image sizes
	bigImageFile = BASEFOLDER + str(filename) + '-big.jpg'

	# Store image for prodList page
	img = Image.open(bigImageFile)

	basewidth = BIG_WIDTH # define the fixed width 
	wpercent = (basewidth / float(img.size[0]))
	hsize = int((float(img.size[1]) * float(wpercent))) # find height which preserves the aspect ratio

	img = img.resize((basewidth, hsize), PIL.Image.ANTIALIAS)

	if img.mode != "RGB":
		img = img.convert("RGB")
	# Writing image to resized file
	img.save(bigImageFile)

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
	cursor.execute( "SELECT image from productDetail order by productid" )
	rows = cursor.fetchall()

	i = 0
	ret = {}
	for row in rows:
		images = row[0].split('$$$')
		for image in images:
			try:
				resizeBigImage(image)
			except Exception, e:
				continue
except _mysql.Error, e:
	sys.exit(1)
finally:
	if con:
		con.close()
