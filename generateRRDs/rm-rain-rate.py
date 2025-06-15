#!/usr/bin/python
import os
import sys
import pprint
import urllib
import urllib2
import datetime
import ConfigParser

Config = ConfigParser.ConfigParser()
Config.read(os.path.expanduser("~/settings.conf"))

lockfile = "/home/weather/generateRRDs/rm-rain-rate-lock"
logfile = "/home/weather/generateRRDs/rm-rain-rate-log"
rrdfile = "/home/weather/public_html/graphs/rain/rm-rain-rate.rrd"

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
lastYear = datetime.datetime.fromtimestamp(int(lastEntryDateTime)).strftime('%Y')

for year in range( int(lastYear), int(datetime.date.today().year)+1 ):
    print "Processing year: "+str(year)

    sqlStatement = '''
    SELECT  UNIX_TIMESTAMP( `Datetime` ) AS  'TimeStamp',  
    `Rain_mm` , @total := @total +  `Rain_mm` AS cumulativeRain
    FROM  `Weather_rainmeter`, (SELECT @total:=0) AS t 
    WHERE YEAR( `Datetime` ) = "'''+str(year)+'''"
    AND `Rain_mm` IS NOT NULL
    ORDER BY TimeStamp ASC
    '''

    cursor.execute(sqlStatement)

    numrows = int(cursor.rowcount)

    print "Entries to add: "+str(numrows)

    for tel in range(0,numrows):
      row = cursor.fetchone()
      cumulative = row[2]
      if cumulative == None: #ignore entries without valid data
        cumulative = 0
        
      if(row[0]>info['last_update']):
        try:  
          rrdtool.update(rrdfile, "%s:%s" %(row[0],int(cumulative*100)))
        except:
          pass
      #print str(row[0])+" : "+str(cumulative)

    #  lastEntryFile = open(logfile, "w")
    #  lastEntryFile.write(str(row[0]))
    #  lastEntryFile.close()

cursor.close()
conn.close()

os.remove(lockfile)
