#!/usr/bin/env python
import ConfigParser, _mysql, sys, __main__
import MySQLdb as mdb

DECAY_FACTOR =0.95

con = None

def getdbsettings():
	config = ConfigParser.ConfigParser()
	config.read('../../db.ini')
	dictionary = {}
	for section in config.sections():
		dictionary[section] = {}
		for option in config.options(section):
			dictionary[section][option] = config.get(section, option)
	return dictionary['connection']

try:
	connectionsettings = getdbsettings()
	con = mdb.connect(connectionsettings['server'], connectionsettings['user'], connectionsettings['password'], connectionsettings['db_name'])
	con.autocommit(True)
	cursor = con.cursor()
	cursor.execute("UPDATE productDetail set score = score * %s",(DECAY_FACTOR))
except _mysql.Error, e:
	sys.exit(1)
finally:
	if con:
		con.commit()
		con.close()
