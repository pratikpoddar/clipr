#!/usr/bin/env python
import ConfigParser
import PIL
import hashlib
import sys
import urllib2
import urllib
import sys
import os
from PIL import Image
from django.conf import settings

SMALL_WIDTH = 200
BIG_WIDTH = 360

BASEFOLDER = settings.PRODUCT_IMAGE_FOLDER

def writeImages(image):
	# finding hashed file name
	filename = hashlib.md5( image ).hexdigest()

	# filename for both image sizes
	originalImageFile = BASEFOLDER + str(filename) + '-orig.jpg'
	smallImageFile = BASEFOLDER + str(filename) + '-small.jpg'
	bigImageFile = BASEFOLDER + str(filename) + '-big.jpg'
	cropImageFile = BASEFOLDER + str(filename) + '-crop.jpg'
	thumbImageFile = BASEFOLDER + str(filename) + '-thumb.jpg'
	# Store tiny image for small thumbnails
	tinyImageFile = BASEFOLDER + str(filename) + '-tiny.jpg'

	try:
		with open( originalImageFile ) as fb: 
			with open( bigImageFile ) as fb:
				with open( smallImageFile ) as fb:
					with open( cropImageFile ) as fb:
						with open( thumbImageFile ) as fb:
							with open( tinyImageFile ) as fb:
								img = Image.open(originalImageFile)
								origWidth = float(img.size[0])
								if (origWidth > BIG_WIDTH and validAspect(image)):
									return 1
								else:
									return 0
	except IOError as e:
		f = open( originalImageFile, 'wb')
		f.write( urllib.urlopen( image ).read() )
		f.close()

		# Store image for prodList page
		img = Image.open(originalImageFile)
		origWidth = float(img.size[0])

		basewidth = SMALL_WIDTH # define the fixed width 
		wpercent = (basewidth / float(img.size[0]))
		hsize = int((float(img.size[1]) * float(wpercent))) # find height which preserves the aspect ratio
		img = img.resize((basewidth, hsize), PIL.Image.ANTIALIAS)

		if img.mode != "RGB":
			img = img.convert("RGB")
		# Writing image to resized file
		img.save(smallImageFile)

		# Store image for product page
		img = Image.open(originalImageFile)

		basewidth = BIG_WIDTH # define the fixed width 
		wpercent = (basewidth / float(img.size[0]))
		hsize = int((float(img.size[1]) * float(wpercent))) # find height which preserves the aspect ratio
		img = img.resize((basewidth, hsize), PIL.Image.ANTIALIAS)
		if img.mode != "RGB":
			img = img.convert("RGB")
		# Writing image to resized file
		img.save(bigImageFile)

		# save cropped image
		cropOrigImage(image)

		# check aspect ratio to determine validity

		if (origWidth > BIG_WIDTH and validAspect(image) ):
			return 1
		else:
			return 0

def validAspect(image):
	# finding hashed file name
	filename = hashlib.md5( image ).hexdigest()

	# filename for both image sizes
	bigImageFile = BASEFOLDER + str(filename) + '-big.jpg'

	# Store image for prodList page
	img = Image.open(bigImageFile)
	aspect = float(img.size[0])/img.size[1]
	return ( aspect < 1.6 and aspect > 0.625 )

def cropOrigImage(image):
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
