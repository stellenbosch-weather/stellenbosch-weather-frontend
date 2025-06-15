#!/usr/bin/python
import os
import sys
import pprint
import urllib
import urllib2
import datetime
import math
import ConfigParser

Config = ConfigParser.ConfigParser()
Config.read(os.path.expanduser("~/settings.conf"))

lockfile = "/home/weather/generateRRDs/mm-winddir-lock"
logfile = "/home/weather/generateRRDs/mm-winddir-log"
rrdfile = "/home/weather/public_html/graphs/wind/mm-winddir.rrd"

table_name = "WeatherMM"
field_name = "direction"

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

sqlStatement = "SELECT UNIX_TIMESTAMP( `TimeStamp` ) AS 'TimeStamp', "+field_name+" FROM "+table_name+" WHERE `TimeStamp`>'"+lastDateTime+"' ORDER BY TimeStamp ASC"

cursor.execute(sqlStatement)

numrows = int(cursor.rowcount)

print "Entries to add: "+str(numrows)

for tel in range(0,numrows):
  row = cursor.fetchone()
  if row[1] == None: #ignore entries without valid data
    continue
  
  #convert to degrees
  if(row[1]=="N"): 
    direction=0
  elif(row[1]=="E"):
    direction=90
  elif(row[1]=="S"):
    direction=180
  elif(row[1]=="W"):
    direction=270
  elif(row[1]=="NE"):
    direction=45
  elif(row[1]=="SE"):
    direction=135
  elif(row[1]=="SW"):
    direction=225
  elif(row[1]=="NW"):
    direction=315
  elif(row[1]=="NNE"):
    direction=22.5
  elif(row[1]=="ENE"):
    direction=67.5
  elif(row[1]=="ESE"):
    direction=112.5
  elif(row[1]=="SSE"):
    direction=157.5
  elif(row[1]=="SSW"):
    direction=202.5
  elif(row[1]=="WSW"):
    direction=247.5
  elif(row[1]=="WNW"):
    direction=292.5
  elif(row[1]=="NNW"):
    direction=337.5
  
  x = math.sin(math.radians(direction))
  y = math.cos(math.radians(direction))
  
  #print "X="+str(x)+" Y="+str(y)
  
  # eers x, dan y
  rrdtool.update(rrdfile, "%s:%s:%s" %(row[0], x, y ))

#  lastEntryFile = open(logfile, "w")
#  lastEntryFile.write(str(row[0]))
#  lastEntryFile.close()

cursor.close()
conn.close()

os.remove(lockfile)
