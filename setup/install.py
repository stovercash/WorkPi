import os
import json
import pymysql as sql
import datetime

today = datetime.date.today()

if not os.path.isfile("secrets.json"):
	os.mknod("secrets.json")
	mysecrets = {}
else:
	mysecrets = json.loads(open("secrets.json").read())

if "vso" not in mysecrets:
	mysecrets["vso"] = {}
if "baseurl" not in mysecrets["vso"]:
	mysecrets["vso"]["baseurl"] = ""
if "user" not in mysecrets["vso"]:
	mysecrets["vso"]["user"] = ""
if "pat" not in mysecrets["vso"]:
	mysecrets["vso"]["pat"] = ""

if "sql" not in mysecrets:
	mysecrets["sql"] = {}
if "dbname" not in mysecrets["sql"]:
	mysecrets["sql"]["dbname"] = "WorkPi"
if "user" not in mysecrets["sql"]:
	mysecrets["sql"]["user"] = ""
if "pass" not in mysecrets["sql"]:
	mysecrets["sql"]["pass"] = ""

if "job_sql" not in mysecrets:
	mysecrets["job_sql"] = {}
if "server" not in mysecrets["job_sql"]:
	mysecrets["job_sql"]["server"] = ""
if "dbname" not in mysecrets["job_sql"]:
	mysecrets["job_sql"]["dbname"] = ""
if "user" not in mysecrets["job_sql"]:
	mysecrets["job_sql"]["user"] = ""
if "pass" not in mysecrets["job_sql"]:
	mysecrets["job_sql"]["pass"] = ""
if "jobtable" not in mysecrets["job_sql"]:
	mysecrets["job_sql"]["jobtable"] = ""

with open("secrets.json", "w") as outfile:
	json.dump(mysecrets,outfile)

con = sql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
cur = con.cursor()
cur.execute("SHOW DATABASES LIKE '" + mysecrets["sql"]["dbname"] + "';")
if not cur.fetchone():
	cur.execute("CREATE DATABASE " + mysecrets["sql"]["dbname"] + ";")

cur.execute("USE " + mysecrets["sql"]["dbname"] + ";")

cur.execute("SHOW TABLES LIKE 'VSOCheckIn';")
if not cur.fetchone():
	cur.execute("CREATE TABLE VSOCheckIn ( EntryNo int NOT NULL, PRIMARY KEY (EntryNo) );")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOCheckIn' AND COLUMN_NAME = 'DateCheckedIn';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOCheckIn ADD DateCheckedIn datetime")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOCheckIn' AND COLUMN_NAME = 'DisplayName';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOCheckIn ADD DisplayName varchar(40)")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOCheckIn' AND COLUMN_NAME = 'UserID';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOCheckIn ADD UserID varchar(10)")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOCheckIn' AND COLUMN_NAME = 'Comment';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOCheckIn ADD Comment varchar(250)")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOCheckIn' AND COLUMN_NAME = 'NoOfObjects';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOCheckIn ADD NoOfObjects int")

cur.execute("SHOW TABLES LIKE 'VSOSetup';")
if not cur.fetchone():
	cur.execute("CREATE TABLE VSOSetup ( PrimaryKey int NOT NULL, PRIMARY KEY (PrimaryKey) );")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOSetup' AND COLUMN_NAME = 'CheckInDownloadedDate';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOSetup ADD CheckInDownloadedDate datetime")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOSetup' AND COLUMN_NAME = 'WordCloudUpdatedEntry';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOSetup ADD WordCloudUpdatedEntry int")

cur.execute("SELECT PrimaryKey FROM VSOSetup")
if not cur.fetchone():
	cur.execute("INSERT INTO VSOSetup (PrimaryKey, CheckInDownloadedDate, WordCloudUpdatedEntry) VALUES (0,'" + today.strftime('%Y-%m-%d') + "', 0);")

cur.execute("SHOW TABLES LIKE 'VSOUser';")
if not cur.fetchone():
	cur.execute("CREATE TABLE VSOUser ( UserID varchar(10) NOT NULL, PRIMARY KEY (UserID) );")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'VSOUser' AND COLUMN_NAME = 'VSOName';")
if not cur.fetchone():
	cur.execute("ALTER TABLE VSOUser ADD VSOName varchar(30)")

cur.execute("SHOW TABLES LIKE 'JobUserStats';")
if not cur.fetchone():
	cur.execute("CREATE TABLE JobUserStats ( UserID varchar(10) NOT NULL, DateChecked datetime );")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'JobUserStats' AND COLUMN_NAME = 'OverdueJobs';")
if not cur.fetchone():
	cur.execute("ALTER TABLE JobUserStats ADD OverdueJobs int")

cur.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'JobUserStats' AND COLUMN_NAME = 'OpenJobHours';")
if not cur.fetchone():
	cur.execute("ALTER TABLE JobUserStats ADD OpenJobHours int")

cur.execute("SHOW TABLES LIKE 'VSOWordCloud';")
if not cur.fetchone():
	cur.execute("CREATE TABLE VSOWordCloud ( Word varchar(100) NOT NULL, Count int );")

con.commit()
cur.close()
con.close()
