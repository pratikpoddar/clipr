#!/usr/bin/env python
import ConfigParser
from optparse import OptionParser
import sys
from addutils import addSingleProduct, refreshStore, refreshAllProducts

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

result = {}
if LINK:
	print addSingleProduct(LINK,USERID)
elif STORE:
	refreshStore(STORE, FORCE)
elif REFRESH:
	refreshAllProducts(FORCE)
else:
	print "No option(link/store/refresh) is provided "
	exit(0)
