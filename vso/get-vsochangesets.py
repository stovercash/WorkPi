import json
import requests
import pymysql as sql
import datetime

today = datetime.date.today()
todayText = today.strftime('%m-%d-%Y')

mysecrets = json.loads(open("../setup/secrets.json").read())
user = mysecrets["vso"]["user"]
pat = mysecrets["vso"]["pat"]
baseurl = mysecrets["vso"]["baseurl"]

con = sql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
cur = con.cursor()
cur.execute("USE " + mysecrets["sql"]["dbname"] + ";")
cur.execute("SELECT DATE_FORMAT(CheckInDownloadedDate,'%m-%d-%Y') AS CheckInDownloadedDate FROM VSOSetup WHERE PrimaryKey = 0")

row = cur.fetchone()
downloadDateText = row[0]

restfunc = 'DefaultCollection/_apis/tfvc/changesets?'
restfunc = restfunc + 'fromDate=' + downloadDateText
restfunc = restfunc + '&toDate=' + todayText
restfunc = restfunc + '&api-version=2.0'

print(restfunc)
results = requests.get(baseurl+restfunc, auth=(user,pat))
print(results.json())

#cur.execute("UPDATE VSOSetup SET CheckInDateDownloaded = '" + todayText "' WHERE PrimaryKey = 0")
