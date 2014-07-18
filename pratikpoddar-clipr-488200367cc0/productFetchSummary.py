import printQueryResults
queriesList = []
queriesList.append(('SELECT siteId, trim(title), productid   from productDetail where productid in (select productid from (select productid, min(time) as t, max(time) as t2 from deletedProducts group by productid having TIMESTAMPDIFF(SECOND, t,CURRENT_TIMESTAMP) < 3600*24*3 and TIMESTAMPDIFF(SECOND, t2,CURRENT_TIMESTAMP) < 3600*24) as pd);', "Products for which refresh failed for the first time in last 3 days and haven't been fixed yet"))
printQueryResults.printResults(queriesList)

