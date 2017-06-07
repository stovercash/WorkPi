import json
import requests
import pymysql as sql
import datetime
import re

pattern = re.compile("\d*$")

today = datetime.date.today()
todayText = today.strftime('%m-%d-%Y')

mysecrets = json.loads(open("/home/pi/dev/WorkPi/setup/secrets.json").read())

con = sql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
cur = con.cursor()
cur.execute("USE " + mysecrets["sql"]["dbname"] + ";")
cur.execute("SELECT WordCloudUpdatedEntry FROM VSOSetup WHERE PrimaryKey = 0")
updcur = con.cursor()
updcur.execute("USE " + mysecrets["sql"]["dbname"] + ";")

row = cur.fetchone()
updatedEntryNo = row[0]
newEntryNo = 0

cur.execute("SELECT Comment, EntryNo FROM VSOCheckIn WHERE EntryNo > %s",(updatedEntryNo))
commentRow = cur.fetchone()
while commentRow:
	comment = commentRow[0]
	comment = comment.replace("."," ")
	comment = comment.replace(","," ")
	comment = comment.replace("/"," ")
	comment = comment.replace("-"," ")
	comment = comment.replace("'"," ")
	comment = comment.replace('"'," ")
	comment = comment.replace("("," ")
	comment = comment.replace(")"," ")
	comment = comment.replace(";"," ")

	commentArray = comment.split()
	for comment in commentArray:
		comment = comment.lower()
		if ((not pattern.match(comment)) and (comment not in mysecrets["vso"]["excludewordcloud"]) and (len(comment) > 2)):
			updcur.execute("SELECT Count FROM VSOWordCloud WHERE Word = %s",(comment))
			countRow = updcur.fetchone()
			if countRow is None:
				updcur.execute("INSERT INTO VSOWordCloud (Word, Count) VALUES (%s,1)",(comment))
			else:
				newcount = countRow[0] + 1
				updcur.execute("UPDATE VSOWordCloud SET Count = %s WHERE Word = %s",(newcount,comment))
	newEntryNo = commentRow[1]
	commentRow = cur.fetchone()

if (newEntryNo != 0):
	cur.execute("UPDATE VSOSetup SET WordCloudUpdatedEntry = %s WHERE PrimaryKey = 0",(newEntryNo))

con.commit()
cur.close()
updcur.close()
con.close()
