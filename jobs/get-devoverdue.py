import json
import pymssql
import pymysql
import datetime

today = datetime.date.today()
todayText = today.strftime('%Y-%m-%d')

mysecrets = json.loads(open("/home/pi/dev/WorkPi/setup/secrets.json").read())

localconn = pymysql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
localcur = localconn.cursor()
localcur.execute("USE " + mysecrets["sql"]["dbname"] + ";")
localcur.execute("SELECT UserID FROM VSOUser")
inscur = localconn.cursor()
inscur.execute("USE " + mysecrets["sql"]["dbname"] + ";")

jobconn = pymssql.connect(host=mysecrets["job_sql"]["server"], user=mysecrets["job_sql"]["user"], password=mysecrets["job_sql"]["pass"], database=mysecrets["job_sql"]["dbname"])
jobcur = jobconn.cursor()

userrow = localcur.fetchone()
while userrow:
	jobcur.execute("SELECT COUNT(*) AS [No of Overdue Logs] FROM " + mysecrets["job_sql"]["jobtable"] + " WHERE ([Person Responsible] = %s) AND (Status <> 3) AND ([Due Date] < %s)",(userrow[0],todayText))
	jobrow = jobcur.fetchone()
	while jobrow:
		inscur.execute("INSERT INTO JobUserStats (UserID, DateChecked, OverdueJobs) VALUES (%s, %s, %s)", (userrow[0],todayText,jobrow[0]))
		jobrow = jobcur.fetchone()
	userrow = localcur.fetchone()

localconn.commit()
inscur.close()
localcur.close()
jobcur.close()
jobconn.close()
localconn.close()
