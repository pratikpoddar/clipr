#!/usr/bin/env python
import ConfigParser
import httplib
import _mysql
import sys
import MySQLdb as mdb
import __main__

def get_status_code( path="/"):
	""" This function retreives the status code of a website by requesting
		HEAD data from the host. This means that it only requests the headers.
		If the host cannot be reached or something else goes wrong, it returns
		None instead.
	"""
	host="www.itsourstudio.com"
	try:
		conn = httplib.HTTPConnection(host)
		conn.request("HEAD", path)
		return conn.getresponse().status
	except StandardError:
		return None

def isPresent(path):
	return (get_status_code("/"+path) / 100) < 4


def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../db.ini')
	dictionary = {}
	for section in config.sections():
		dictionary[section] = {}
		for option in config.options(section):
			dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

def update(cursor,id,suffix):
	link = "http://www.itsourstudio.com/" + suffix
	cursor.execute("UPDATE productDetail set link = %s where productid = %s",(link, str(id)))

con = None
try:
	#stats
	connectionsettings = getdbsettings()
	con = mdb.connect(connectionsettings['server'], connectionsettings['user'], connectionsettings['password'], connectionsettings['db_name'])
	con.autocommit(True)

	cursor = con.cursor()

	cursor.execute("SELECT productid,link,title from productDetail where siteId = 'itsourstudio'")
	rows = cursor.fetchall()
	for row in rows:
		title = row[2]
		productid = row[0]
		suffix = title.lower()
		if isPresent(suffix.strip().replace(" ","-")):
			update(cursor, productid, suffix.strip().replace(" ","-"))
		elif isPresent(suffix.strip().replace(" ","")):
			update(cursor, productid, suffix.strip().replace(" ",""))
		elif isPresent(suffix.split("(")[0].strip().replace(" ","-")):
			update(cursor, productid, suffix.split("(")[0].strip().replace(" ","-"))
		elif isPresent(suffix.split("(")[0].strip().replace(" ","")):
			update(cursor, productid, suffix.split("(")[0].strip().replace(" ",""))
		else:
			print str(productid) + " : " + "not found"

except _mysql.Error, e:
	sys.exit(1)
finally:
	if con:
		cursor = con.cursor()
		con.commit()
		con.close()
