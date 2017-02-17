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

changesetsfunc = 'DefaultCollection/_apis/tfvc/changesets?'
changesetsfunc = changesetsfunc + 'fromDate=' + downloadDateText + '-12:00AM'
changesetsfunc = changesetsfunc + '&toDate=' + todayText
changesetsfunc = changesetsfunc + '&api-version=2.0'

singlechangefunc = 'DefaultCollection/_apis/tfvc/changesets/'
singlechangesuffix = '/changes?api-version=2.0'

#print(baseurl+changesetsfunc)
results = requests.get(baseurl+changesetsfunc, auth=(user,pat))
#print(results.json())
jres = results.json()
#print(jres)

for changeset in jres["value"]:
	cur.execute("SELECT EntryNo FROM VSOCheckIn WHERE EntryNo = %s",(changeset["changesetId"]))
	if not cur.fetchone():
#		print(baseurl+singlechangefunc+str(changeset["changesetId"])+singlechangesuffix)
		results = requests.get(baseurl+singlechangefunc+str(changeset["changesetId"])+singlechangesuffix, auth=(user,pat))
		jsingle = results.json()
#		print(str(jsingle["count"]))
		cur.execute("INSERT INTO VSOCheckIn (EntryNo,DateCheckedIn,DisplayName,Comment,NoOfObjects) VALUES (%s,%s,%s,%s,%s)",(changeset["changesetId"],changeset["createdDate"],changeset["author"]["displayName"],changeset["comment"],jsingle["count"]))

cur.execute("UPDATE VSOSetup SET CheckInDownloadedDate = '" + today.strftime('%Y-%m-%d') +  "' WHERE PrimaryKey = 0")

con.commit()
cur.close()
con.close()
