#!/usr/bin/python
import os
import sys
import pprint
import urllib
import urllib2
import datetime
import time
from calendar import monthrange
import ConfigParser

Config = ConfigParser.ConfigParser()
Config.read(os.path.expanduser("~/settings.conf"))

lockfile = "/home/weather/generateRRDs/eb-rain-month-lock"
logfile = "/home/weather/generateRRDs/eb-rain-month-log"
rrdfile = "/home/weather/public_html/graphs/rain/eb-rain-month.rrd"

try:
    import MySQLdb
except:
    print "ERROR !!!!\nMySQLdb not installed."
    sys.exit(1)
    
try:
    import rrdtool
except:
    print "ERROR !!!!\nRRDtool not installed."
    sys.exit(1)

#This is to check if there is already a lock file existing#
if os.access(lockfile, os.F_OK):
  #if the lockfile is already there then check the PID number 
  #in the lock file
  pidfile = open(lockfile, "r")
  pidfile.seek(0)
  oldpid = pidfile.readline()
  # Now we check the PID from lock file matches to the current
  # process PID
  if oldpid.strip() != "" and os.path.exists("/proc/%s" % oldpid):
    print "You already have an instance of the program running"
    print "It is running as process %s," % oldpid
    sys.exit(1)
  else:
    print "File is there but the program is not running"
    print "Removing lock file for the: %s as it can be there because of the program last time it was run" % oldpid
    os.remove(lockfile)

#This is part of code where we put a PID file in the lock file
pidfile = open(lockfile, "w")
newpid = str(os.getpid())
print "PID="+newpid
pidfile.write(newpid)
pidfile.close()


# Database connection
conn = MySQLdb.connect (host = Config.get("database", "host"),
                    user = Config.get("database", "username"),
                    passwd = REDACTED "password"),
                    db = Config.get("database", "database"))
cursor = conn.cursor ()


info = rrdtool.info(rrdfile)
print "Last rrd update: "+str(info['last_update'])

lastEntryDateTime=str(info['last_update'])

lastDateTime = datetime.datetime.fromtimestamp(int(lastEntryDateTime)).strftime('%Y-%m-%d %H:%M:%S')
lastYear = int( datetime.datetime.fromtimestamp(int(lastEntryDateTime)).strftime('%Y') )
lastMonth = int( datetime.datetime.fromtimestamp(int(lastEntryDateTime)).strftime('%m') )

currentYear = int(datetime.date.today().year)

for year in range( int(lastYear), currentYear+1 ):
  if year == lastYear:
    startMonth = lastMonth
  else:
    startMonth = 1
    
  for month in range(startMonth, 13): 
    print "Processing month: "+str(year)+"-"+str(month),

    sqlStatement = '''
    SELECT  UNIX_TIMESTAMP( `Date` ) AS  'TimeStamp',  
    `Rain` , @total := @total +  `Rain` AS cumulativeRain
    FROM  `Elsenburg`, (SELECT @total:=0) AS t 
    WHERE YEAR( `Date` ) = "'''+str(year)+'''"
    AND MONTH( `Date` ) = "'''+str(month)+'''"
    ORDER BY TimeStamp ASC
    '''
    #print sqlStatement

    cursor.execute(sqlStatement)

    numrows = int(cursor.rowcount)
    print "found "+str(numrows)+" days with data"
    
    info = rrdtool.info(rrdfile)

    if(numrows==1):
        unixtimestampcurrent=time.mktime(datetime.date(year,month,1).timetuple())
        row = cursor.fetchone()
        cumulative = row[2]
        if cumulative == None: #ignore entries without valid data
          cumulative = 0

        if(row[0]>info['last_update']):
          print(datetime.datetime.fromtimestamp(row[0]).strftime('%Y-%m-%d %H:%M:%S'))
          
          time_to_add=row[0]-(monthrange(year,month)[1]-1)*86400
          
          for copyday in range(1,monthrange(year,month)[1]):
              rrdtool.update(rrdfile, "%s:%s" %(time_to_add,cumulative))
              time_to_add=time_to_add+86400

    else:
      for tel in range(0,numrows):
        row = cursor.fetchone()
        cumulative = row[2]
        if cumulative == None: #ignore entries without valid data
          print "No data"
          cumulative = 0
          
        if(row[0]>info['last_update']):
          print(datetime.datetime.fromtimestamp(row[0]).strftime('Adding %Y-%m-%d %H:%M:%S'))
          rrdtool.update(rrdfile, "%s:%s" %(row[0],cumulative))

cursor.close()
conn.close()

os.remove(lockfile)
