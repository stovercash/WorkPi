
import json
import requests
import pymysql as sql
import datetime

today = datetime.date.today()
todayText = today.strftime('%m-%d-%Y')

mysecrets = json.loads(open("/home/pi/dev/WorkPi/setup/secrets.json").read())
user = mysecrets["vso"]["user"]
pat = mysecrets["vso"]["pat"]
baseurl = mysecrets["vso"]["baseurl"]

con = sql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
cur = con.cursor()
cur.execute("USE " + mysecrets["sql"]["dbname"] + ";")
cur.execute("SELECT CheckInLastId FROM VSOSetup WHERE PrimaryKey = 0")

row = cur.fetchone()
checkInLastId = row[0]

changesetsfunc = 'DefaultCollection/_apis/tfvc/changesets?maxCommentLength=250&'
changesetsfunc = changesetsfunc + 'fromId=' + str(checkInLastId)
changesetsfunc = changesetsfunc + '&api-version=2.0'

singlechangefunc = 'DefaultCollection/_apis/tfvc/changesets/'
singlechangesuffix = '/changes?api-version=2.0'

results = requests.get(baseurl+changesetsfunc, auth=(user,pat))
#print(results)
jres = results.json()

for changeset in jres["value"]:
	if checkInLastId < changeset["changesetId"]:
		checkInLastId = changeset["changesetId"]
	if "comment" not in changeset:
		changesetComment = ""
	else:
		changesetComment = changeset["comment"]
	if changesetComment not in mysecrets["vso"]["excludecomments"]:
		cur.execute("SELECT EntryNo FROM VSOCheckIn WHERE EntryNo = %s",(changeset["changesetId"]))
		if not cur.fetchone():
			cur.execute("SELECT UserID FROM VSOUser WHERE VSOName = %s",(changeset["author"]["displayName"]))
			UserRow = cur.fetchone()
			if UserRow is None:
				VSOName = changeset["author"]["displayName"]
				UserID = VSOName[0] + VSOName.split()[1][0]
				cur.execute("INSERT INTO VSOUser (UserID,VSOName) VALUES (%s,%s)",(UserID,VSOName))
			else:
				UserID = UserRow[0]
			results = requests.get(baseurl+singlechangefunc+str(changeset["changesetId"])+singlechangesuffix, auth=(user,pat))
			jsingle = results.json()
			cur.execute("INSERT INTO VSOCheckIn (EntryNo,UserID,DateCheckedIn,DisplayName,Comment,NoOfObjects) VALUES (%s,%s,%s,%s,%s,%s)",(changeset["changesetId"],UserID,changeset["createdDate"],changeset["author"]["displayName"],changesetComment,jsingle["count"]))

cur.execute("UPDATE VSOSetup SET CheckInDownloadedDate = '" + today.strftime('%Y-%m-%d') +  "', CheckInLastId = " + str(checkInLastId) + " WHERE PrimaryKey = 0")

con.commit()
cur.close()
con.close()
