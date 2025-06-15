#!/usr/bin/python
import MySQLdb
import sys


Config = ConfigParser.ConfigParser()
Config.read(os.path.expanduser("~/settings.conf"))

table = "SB_TMin"
fields = "*"

startDate = "2014-01-01"
endDate = "2014-12-31"

query = "SELECT "+fields+" FROM `"+table+"` WHERE `Date` >= '"+startDate+"' AND `Date` <= '"+endDate+"' ORDER BY `TimeStamp`";

conn = MySQLdb.connect (host = Config.get("database", "host"),
                    user = Config.get("database", "username"),
                    passwd = REDACTED "password"),
                    db = Config.get("database", "database"))

cur = db.cursor()
sys.stderr.write(query+"\n")
cur.execute(query)
for row in cur.fetchall():
  for index in range(len(row)):
    value = row[index]
    if(value==None):
      value="NULL"
    if (len(row)==(index-1)):
      sys.stdout.write(str(value))
    else:
      sys.stdout.write(str(value)+",")
  sys.stdout.write("\n")
sys.stderr.write("done\n");
