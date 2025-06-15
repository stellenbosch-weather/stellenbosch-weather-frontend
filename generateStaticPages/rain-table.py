#!/usr/bin/python
import os
import sys
import pprint
import urllib
import urllib2
import datetime
from pprint import pprint
import ConfigParser

Config = ConfigParser.ConfigParser()
Config.read(os.path.expanduser("~/settings.conf"))

lockfile = "/home/weather/generateStaticPages/rm-rain-lock"
logfile = "/home/weather/generateStaticPages/rm-rain-log"
outfile = "/home/weather/public_html/tableRain2.php"

try:
    import MySQLdb
except:
    print "ERROR !!!!\nMySQLdb not installed."
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


#generator function
def daterange(start_date, end_date):
    for n in range(int ((end_date - start_date).days)):
        yield start_date + datetime.timedelta(n)


# Database connection
conn = MySQLdb.connect (host = Config.get("database", "host"),
                    user = Config.get("database", "username"),
                    passwd = REDACTED "password"),
                    db = Config.get("database", "database"))
cursor = conn.cursor ()


enableMM = True
enableMR = True
enableEE = True
enableRM = True
enableDC = True


sqlStatement = "SELECT MAX(Date) FROM `aggregate_rain`"
cursor.execute(sqlStatement)
row = cursor.fetchone()
if(row[0]==None):
  start_date = datetime.date(2008,9,3) #the first data we have ever logged
else:
  start_date = row[0]+datetime.timedelta(days=1)

print "Starting at date: "+str(start_date)

end_date = datetime.date.today()

print "Stopping before: "+str(end_date)

for day in daterange(start_date, end_date):
    print "Processing: "+str(day)+""

    year=day.year
    month=day.month
    values = []
    
    if enableMM and year<2014:
      print "mm"
      sqlStatement = "SELECT SUM(`rain`) FROM `WeatherMM` WHERE DATE(`date`)=\""+str(day)+"\""
      cursor.execute(sqlStatement)
      row = cursor.fetchone()
      values.append(row[0])
    
    if enableMR and year>2013:
      print "mr"
      sqlStatement = "SELECT SUM(`rain`) FROM `Weather_mmroof` WHERE DATE(`date`)=\""+str(day)+"\""
      cursor.execute(sqlStatement)
      row = cursor.fetchone()
      values.append(row[0])
    
    if enableEE and year<2014:
      print "ee"
      sqlStatement = "SELECT SUM(`rain`) FROM `WeatherEE` WHERE DATE(`date`)=\""+str(day)+"\""
      cursor.execute(sqlStatement)
      row = cursor.fetchone()
      values.append(row[0])
    
    if enableRM and year>2013:
      print "rm"
      sqlStatement = "SELECT SUM(`Rain_mm`) FROM `Weather_rainmeter` WHERE DATE(`Datetime`)=\""+str(day)+"\""
      cursor.execute(sqlStatement)
      row = cursor.fetchone()
      values.append(row[0])
    
    if enableDC and year>2013 and year<2019:
      print "dc"
      sqlStatement = '''
      SELECT UNIX_TIMESTAMP( `DateTime` ) AS 'TimeStamp', 
      @total := @total + (`Rain`-@prev) AS cumulativeRain,
      `Rain`,
      @prev := `Rain`
      FROM `Weather_decingel`, 
      (SELECT @prev:=(
        SELECT `Rain` 
        FROM `Weather_decingel` 
        WHERE DATE( `DateTime` ) = "'''+str(day)+'''" 
        ORDER BY DateTime ASC 
        LIMIT 1
      ), @total:=0) AS p 
      WHERE DATE( `DateTime` ) = "'''+str(day)+'''"
      AND `Rain` IS NOT NULL
      ORDER BY `DateTime` ASC
      '''
      cursor.execute(sqlStatement)

      dc_value = -1
      numrows = int(cursor.rowcount)
      for tel in range(0,numrows):
        row = cursor.fetchone()
        dc_value = row[1]
      
      if dc_value>=0:
        values.append(dc_value)
    
    if enableDC and year>2018:
      print "dc"
      sqlStatement = "SELECT SUM(`Rain`) FROM `Weather_decingel` WHERE `Date`=\""+str(day)+"\""
      cursor.execute(sqlStatement)
      row = cursor.fetchone()
      values.append(row[0])

    print values
    max_value = max(values)
    if(max_value==None):
      max_value=-1
    sqlStatement = "INSERT INTO `aggregate_rain` (`Date`,`Rain`) VALUES (\""+str(day)+"\","+str(max_value)+")"
    print "Setting rain to "+str(max_value)+"",
    result = cursor.execute(sqlStatement)
    if result==1:
      print "Success"
    else:
      print "Failed"


cursor.close()
conn.close()

os.remove(lockfile)
