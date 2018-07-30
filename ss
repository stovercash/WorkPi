[1mdiff --git a/eink/update-eink.py b/eink/update-eink.py[m
[1mindex 4cbf8cc..d03f3aa 100644[m
[1m--- a/eink/update-eink.py[m
[1m+++ b/eink/update-eink.py[m
[36m@@ -42,27 +42,32 @@[m [mnext_time = next_time + timedelta(minutes=30)[m
 file = open("/home/pi/dev/WorkPi/cal/calendar.ics", "rb")[m
 ical = icalendar.Calendar.from_ical(file.read())[m
 for event in ical.walk("VEVENT"):[m
[32m+[m	[32mskip = False[m
 	if (event.get("X-MICROSOFT-CDO-ALLDAYEVENT") == "FALSE") and (event.get("X-MICROSOFT-CDO-BUSYSTATUS") == "BUSY"):[m
 		event_dtstart = event.get("DTSTART").dt[m
 		event_dtend = event.get("DTEND").dt[m
 		if (event.get("RRULE") is not None):[m
 			rule = rrule.rrulestr(event.get("RRULE").to_ical().decode('utf-8'), dtstart=event_dtstart)[m
 			start_time = rule.after(current_time)[m
[31m-			if start_time.strftime("%Z") == "EST":[m
[31m-				start_time = start_time.replace(tzinfo=None)[m
[31m-				start_time = pytz.timezone("America/New_York").localize(start_time)[m
[31m-			end_time = start_time + (event_dtend - event_dtstart)[m
[31m-			start_time = start_time - timedelta(minutes=5)[m
[32m+[m			[32mif type(start_time) is datetime:[m
[32m+[m				[32mif start_time.strftime("%Z") == "EST":[m
[32m+[m					[32mstart_time = start_time.replace(tzinfo=None)[m
[32m+[m					[32mstart_time = pytz.timezone("America/New_York").localize(start_time)[m
[32m+[m				[32mend_time = start_time + (event_dtend - event_dtstart)[m
[32m+[m				[32mstart_time = start_time - timedelta(minutes=5)[m
[32m+[m			[32melse:[m
[32m+[m				[32mskip = True[m
 		else:[m
 			start_time = event_dtstart - timedelta(minutes=5)[m
 			end_time = event_dtend[m
[31m-		if (start_time < current_time) and (end_time > current_time):[m
[31m-			screen_title = event.get("SUMMARY")[m
[31m-			screen_title = screen_title[:100][m
[31m-			screen_category = "MEETING"[m
[31m-			screen_time = event_dtstart.strftime("%-I:%M %p") + " - " + event_dtend.strftime("%-I:%M %p")[m
[31m-		if (start_time > current_time) and (start_time < next_time):[m
[31m-			next_time = start_time[m
[32m+[m		[32mif not skip:[m
[32m+[m			[32mif (start_time < current_time) and (end_time > current_time):[m
[32m+[m				[32mscreen_title = event.get("SUMMARY")[m
[32m+[m				[32mscreen_title = screen_title[:100][m
[32m+[m				[32mscreen_category = "MEETING"[m
[32m+[m				[32mscreen_time = event_dtstart.strftime("%-I:%M %p") + " - " + event_dtend.strftime("%-I:%M %p")[m
[32m+[m			[32mif (start_time > current_time) and (start_time < next_time):[m
[32m+[m				[32mnext_time = start_time[m
 file.close()[m
 [m
 current_time = datetime.now()[m
[1mdiff --git a/html/index.php b/html/index.php[m
[1mindex b9f851c..51dbc8f 100755[m
[1m--- a/html/index.php[m
[1m+++ b/html/index.php[m
[36m@@ -70,7 +70,7 @@[m [minclude 'jobsoverduebyuser.php';[m
 <div style="width: 420px; height: 215px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; padding: 20px; float: left">[m
 <div id="chart_div_cloud" style="width: 100%; height: 100%;"></div>[m
 </div>[m
[31m-<div style="width: 420px; height: 215px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; padding: 20px; float: left">[m
[32m+[m[32m<div style="width: 600px; height: 215px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; padding: 20px; float: left">[m
 <div style="width: 100%; height: 20px; float: left; text-align: center; font-size: 20px"><span style="position: relative; top: -4px; color: LightGray">Open Job Hours</span></div>[m
 <div id="chart_div_jobhours" style="width: 100%; height: auto; float: left; clear: left; position: relative; top: -4px"></div>[m
 </div>[m
[1mdiff --git a/html/jobsopenjobhours.php b/html/jobsopenjobhours.php[m
[1mindex 681558f..72897ef 100644[m
[1m--- a/html/jobsopenjobhours.php[m
[1m+++ b/html/jobsopenjobhours.php[m
[36m@@ -115,7 +115,8 @@[m [mwhile ($dateLoop <= $dateEnd)[m
 	var options3 = {[m
 		isStacked: true,[m
 		fontName: 'Consolas',[m
[31m-		chartArea: {width:'300',height:'142'},[m
[32m+[m		[32mchartArea: {width:'450',height:'125'},[m
[32m+[m		[32mhAxis: {textStyle: {fontSize: 10}}[m
 	}[m
 [m
 	var chart3 = new google.visualization.AreaChart(document.getElementById('chart_div_jobhours'));[m
[1mdiff --git a/vso/get-vsochangesets.py b/vso/get-vsochangesets.py[m
[1mindex eacd72f..c7f42c9 100644[m
[1m--- a/vso/get-vsochangesets.py[m
[1m+++ b/vso/get-vsochangesets.py[m
[36m@@ -28,7 +28,7 @@[m [msinglechangefunc = 'DefaultCollection/_apis/tfvc/changesets/'[m
 singlechangesuffix = '/changes?api-version=2.0'[m
 [m
 results = requests.get(baseurl+changesetsfunc, auth=(user,pat))[m
[31m-#print(results.json())[m
[32m+[m[32m#print(results)[m
 jres = results.json()[m
 [m
 for changeset in jres["value"]:[m
