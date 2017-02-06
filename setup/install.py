import os
import json
import pymysql as sql

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


with open("secrets.json", "w") as outfile:
	json.dump(mysecrets,outfile)

con = sql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
cur = con.cursor()
cur.execute("SHOW DATABASES LIKE '" + mysecrets["sql"]["dbname"] + "'")
if not cur.fetchone():
	cur.execute("CREATE DATABASE " + mysecrets["sql"]["dbname"] + ";")
