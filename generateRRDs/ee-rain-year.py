#!/usr/bin/python
import os
import sys
import pprint
import urllib
import urllib2
import datetime
import time
import ConfigParser

Config = ConfigParser.ConfigParser()
Config.read(os.path.expanduser("~/settings.conf"))

lockfile = "/home/weather/generateRRDs/ee-rain-year-lock"
logfile = "/home/weather/generateRRDs/ee-rain-year-log"
rrdfile = "/home/weather/public_html/graphs/rain/ee-rain-year.rrd"

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

currentYear = int(datetime.date.today().year)

for year in range( int(lastYear), currentYear+1 ):
  print "Processing year: "+str(year)

  sqlStatement = '''
  SELECT  UNIX_TIMESTAMP( `timestamp` ) AS  'TimeStamp',  
  `rain` , @total := @total +  `rain` AS cumulativeRain
  FROM  `WeatherEE`, (SELECT @total:=0) AS t 
  WHERE YEAR( `date` ) = "'''+str(year)+'''"
  ORDER BY TimeStamp ASC
  '''

  cursor.execute(sqlStatement)

  numrows = int(cursor.rowcount)
  
  info = rrdtool.info(rrdfile)
  
  print "Entries to add: "+str(numrows)
  for tel in range(0,numrows):
    row = cursor.fetchone()
    cumulative = row[2]
    if cumulative == None: #ignore entries without valid data
      cumulative = 0
      
    if(row[0]>info['last_update']):
      rrdtool.update(rrdfile, "%s:%s" %(row[0],cumulative))
  
  # add a 0 at the start of the next year
  info = rrdtool.info(rrdfile)
  dt = datetime.datetime.strptime(str(year+1), '%Y')
  ts = time.mktime(dt.timetuple())
  if(year < currentYear and numrows>0 and ts>info['last_update']):
    print "Adding 2 days of zeros to beginning of "+str(year+1)
    for timestamp in range( int(ts/60), int( ( ts+(1440*2*60) )/60 ) ):
      print ".",
      rrdtool.update(rrdfile, "%s:%s" %(timestamp*60,0))
      


cursor.close()
conn.close()

os.remove(lockfile)
