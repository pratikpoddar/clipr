import sys
import os

import urllib2
import urllib

import _mysql
import MySQLdb as mdb

from scrapy.spider import BaseSpider
from scrapy.http import Request, TextResponse, Response
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.selector import HtmlXPathSelector

from optparse import OptionParser

currDir = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, currDir+'/flipkart/flipkart/spiders')
from updateLinks import parseLink

# # # # # # # # # # # # Imports end
parser = OptionParser()

parser.set_defaults(listToParse="")
parser.set_defaults(genreToParse="")
parser.set_defaults(categoryName="")

parser.add_option( "-l", "--list", dest="listToParse",
                  help="link of the good reads list to be parsed ", metavar="LIST")
parser.add_option( "-g", "--genre", dest="genreToParse",
                  help="link of the good reads genre to be parsed ", metavar="GENRE")
parser.add_option( "-n", "--name", dest="categoryName",
                  help="name of the category to which these lists should belong (of the form word1_word2) ", metavar="CATEGORYNAME")
(options, args) = parser.parse_args()

parsedArgs = parser.parse_args()[0]

# # # # # # # # # # # # input options parser

LIST = str(parsedArgs.listToParse)
GENRE = str(parsedArgs.genreToParse)
CATEGORY = str(parsedArgs.categoryName)

BOOK_PREFIX = "Clipr_Book_"

if (not CATEGORY) or CATEGORY.find(' ') > 0:
    print CATEGORY
    print CATEGORY.find(' ')
    print "You must specify a non-empty category name (without spaces)"
    sys.exit(1)


CATEGORY = BOOK_PREFIX + CATEGORY
# # # # # # # # # # # # inputs end

HEAD = 'www.goodreads.com'
sp = BaseSpider('some')
# # # # # # # # # # # # global variables end
def httpfy(link):
	if link.startswith('http://'):
		return link
	else:
		return 'http://'+link

def parseBookList(bookLinks):
    
    global cursor
    global groupid

    for bookLink in bookLinks:

        tail = bookLink
        
        # creating an http connection
        req = urllib2.Request(httpfy(HEAD+tail))
        
        # creating response
        resp = urllib2.urlopen( req )
        respText = resp.read()

        # creating scrapy request and response
        sresp = TextResponse(url=resp.geturl(), status=200,body=respText,encoding='utf-8')

        productid = parseLink( """http://www.flipkart.com/search/a/books?query=""" + str( getISBN( sresp ) ) )
        if not productid:
            print "skipping book with isbn code: not found or error in parsing" + str( getISBN( sresp ) )
        else:
            cursor.execute("INSERT into productGroup(productid, groupid) select distinct %s, %s from productGroup \
                where not exists(select * from productGroup where productid = %s and groupid = %s)", 
                (str(productid), str(groupid),str(productid),str(groupid) ) )



def getISBN(response):
    site = HtmlXPathSelector(response)
    return site.select('//meta[@property="good_reads:isbn"]/@content').extract()[0]


def getAllLinksInList(listLink):

    #database cursor
    tail = listLink.split(HEAD)[1]
    
    # creating an http connection
    req = urllib2.Request(httpfy(HEAD+tail))
    
    # creating response
    resp = urllib2.urlopen( req )
    respText = resp.read()

    # creating scrapy request and response
    sresp = TextResponse(url=resp.geturl(),status=200,body=respText,encoding='utf-8')

    site = HtmlXPathSelector(sresp)

    return site.select('//table[@class="tableList"]//tr[@itemtype="http://schema.org/Book"]//a[@class="bookTitle"]/@href').extract()
    
def getAllLinksInGenre(listLink):
    #database cursor
    tail = listLink.split(HEAD)[1]
    
    req = urllib2.Request(httpfy(HEAD+tail))
    
    # creating response
    resp = urllib2.urlopen( req )
    respText = resp.read()

    # creating scrapy request and response
    sresp = TextResponse(url=resp.geturl(),status=200,body=respText,encoding='utf-8')
    
    site = HtmlXPathSelector(sresp)

    return site.select('//div[@class="leftContainer"]//div[@class="elementList"]//div[@class="left"]//a[@class="bookTitle"]/@href').extract()


con = None

try:

    con = mdb.connect(host='localhost' ,user='root' ,passwd='12345678',db='clipr', charset = "utf8", use_unicode = True)
    con.autocommit(True)
    cursor = con.cursor()

    cursor.execute("INSERT into groupTable(groupname) select distinct\
        %s from groupTable where not exists (SELECT * from groupTable where groupname = %s)",( CATEGORY, CATEGORY))

    groupid = cursor.lastrowid
    if not groupid:
        cursor.execute("SELECT id from groupTable where groupname = %s",(CATEGORY))
        groupid = cursor.fetchone()[0]


    if LIST:
        if LIST.startswith('http://www.goodreads.com/list/show/') or LIST.startswith('www.goodreads.com/list/show/'):
            parseBookList( getAllLinksInList( LIST ) )
    elif GENRE:
        if GENRE.startswith('http://www.goodreads.com/shelf/') or GENRE.startswith('www.goodreads.com/shelf/'):
            parseBookList( getAllLinksInGenre( GENRE ))
except _mysql.Error, e:
	print "Error %d: %s" % (e.args[0], e.args[1])
	sys.exit(1)