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
	jobcur.execute("SELECT SUM([Initial Budget Hours]) AS [Open Job Hours] FROM " + mysecrets["job_sql"]["jobtable"] + " WHERE ([Person Responsible] = %s) AND ([Invoice Status] < 3) AND ([Billing status] = 2 ) AND (Priority > 0) AND (Priority < 9)",(userrow[0]))
	jobrow = jobcur.fetchone()
	if inscur.execute("SELECT UserID, DateChecked FROM JobUserStats WHERE (UserID = %s) AND (DateChecked = %s)", (userrow[0],todayText)):
		inscur.execute("UPDATE JobUserStats SET OpenJobHours = %s WHERE  (UserID = %s) AND (DateChecked = %s)", (jobrow[0],userrow[0],todayText))
	else:
		inscur.execute("INSERT INTO JobUserStats (UserID, DateChecked, OpenJobHours) VALUES (%s, %s, %s)", (userrow[0],todayText,jobrow[0]))
	userrow = localcur.fetchone()

localconn.commit()
inscur.close()
localcur.close()
jobcur.close()
jobconn.close()
localconn.close()
