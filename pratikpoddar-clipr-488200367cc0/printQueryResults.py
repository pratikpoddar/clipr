#!/usr/bin/env python
import ConfigParser
import json
import _mysql
import sys
import MySQLdb as mdb
import os

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../db.ini')
	dictionary = {}
	for section in config.sections():
		dictionary[section] = {}
		for option in config.options(section):
			dictionary[section][option] = config.get(section, option)
	return dictionary['connection']


def toUtf(string):
	return (unicode(string)).encode("utf-8")

def printResults(queriesList):
	con = None
	try:
		connectionsettings = getdbsettings()
		con = mdb.connect(host=connectionsettings['server'] ,user=connectionsettings['user'] ,passwd=connectionsettings['password'],db=connectionsettings['db_name'], charset = "utf8", use_unicode = True)
		#database cursor
		cursor = con.cursor()
		for query in queriesList:
			cursor.execute(query[0])
			rows = cursor.fetchall()
			print query[1]
			print "---------------------------"
			for row in rows:
				res = ""
				isFirst = True
				for elem in row:
					if isFirst:
						res = res + str(toUtf(elem))
						isFirst = False
					else:
						try:
							res = res + " , " + str(toUtf(elem))
						except:
							res = res + " , " + "missing"
				print res
			print "================================================================"
		con.commit()
	except _mysql.Error, e:
		print "Error %d: %s" % (e.args[0], e.args[1])
		sys.exit(1)
	finally:
		if con:
			con.commit()
			con.close()
