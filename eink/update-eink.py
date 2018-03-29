import json
import sys
import icalendar
from datetime import datetime, date, time, timedelta
from dateutil import rrule
import pytz
import pymysql
import os
import textwrap

import epd2in7b
import Image
import ImageFont
import ImageDraw

COLORED = 1
UNCOLORED = 0

current_time = datetime.now(pytz.timezone("America/New_York"))

mysecrets = json.loads(open("/home/pi/dev/WorkPi/setup/secrets.json").read())
localconn = pymysql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
localcur = localconn.cursor()

localcur.execute("USE " + mysecrets["sql"]["dbname"] + ";")

localcur.execute("SELECT NextRefreshTime FROM eInkDisplay")
if localcur.rowcount > 0:
	sqlDisp = localcur.fetchone()
	if sqlDisp[0] is not None:
		next_time = pytz.timezone("America/New_York").localize(sqlDisp[0])
		if next_time > current_time:
			sys.exit(0)

screen_category = ""
screen_title = ""
screen_time = ""

next_time = datetime.now(pytz.timezone("America/New_York"))
next_time = next_time + timedelta(minutes=30)

file = open("/home/pi/dev/WorkPi/cal/calendar.ics", "rb")
ical = icalendar.Calendar.from_ical(file.read())
for event in ical.walk("VEVENT"):
	if (event.get("X-MICROSOFT-CDO-ALLDAYEVENT") == "FALSE") and (event.get("X-MICROSOFT-CDO-BUSYSTATUS") == "BUSY"):
		event_dtstart = event.get("DTSTART").dt
		event_dtend = event.get("DTEND").dt
		if (event.get("RRULE") is not None):
			rule = rrule.rrulestr(event.get("RRULE").to_ical().decode('utf-8'), dtstart=event_dtstart)
			start_time = rule.after(current_time)
			end_time = start_time + (event_dtend - event_dtstart)
			start_time = start_time - timedelta(minutes=5)
		else:
			start_time = event_dtstart - timedelta(minutes=5)
			end_time = event_dtend
		if (start_time < current_time) and (end_time > current_time):
			screen_title = event.get("SUMMARY")
			screen_title = screen_title[:100]
			screen_category = "MEETING"
			screen_time = event_dtstart.strftime("%-I:%M %p") + " - " + event_dtend.strftime("%-I:%M %p")
		if (start_time > current_time) and (start_time < next_time):
			next_time = start_time
file.close()

current_time = datetime.now()

if (current_time.time() < time(8,0)) and (next_time.time() > time(8,0)):
	next_time = datetime(current_time.year, current_time.month, current_time.day, 8, 8, 0)
if (current_time.time() < time(12,0)) and (next_time.time() > time(12,0)):
	next_time = datetime(current_time.year, current_time.month, current_time.day, 12, 0, 0)
if (current_time.time() < time(18,0)) and (next_time.time() > time(18,0)):
	next_time = datetime(current_time.year, current_time.month, current_time.day, 18, 0, 0)

if screen_category == "":
	if (current_time.time() >= time(8,0)) and (current_time.time() < time(9,0)):
		screen_title = ""
		screen_category = "MORNING"
		screen_time = ""
	if (current_time.time() >= time(12,0)) and (current_time.time() < time(14,0)):
		screen_title = "Eat Lunch"
		screen_category = "LUNCH"
		screen_time = ""
	if (current_time.time() >= time(18,0)) and (current_time.time() < time(19,0)):
		screen_title = ""
		screen_category = "NIGHT"
		screen_time = ""

if screen_category == "":
	screen_title = ""
	screen_category = "BLANK"
	screen_time = ""

localcur.execute("SELECT CurrentCategory, CurrentTitle, LastTimeRefreshed FROM eInkDisplay")
if localcur.rowcount > 0:
	sqlDisp = localcur.fetchone()
	if (sqlDisp[0] == screen_category) and (sqlDisp[1] == screen_title):
		localcur.execute("DELETE FROM eInkDisplay")
		localcur.execute("INSERT INTO eInkDisplay (CurrentCategory, CurrentTitle, LastTimeRefreshed, NextRefreshTime) VALUES (%s, %s, %s, %s)", (sqlDisp[0],sqlDisp[1],sqlDisp[2],next_time))
		localconn.commit()
		localcur.close()
		localconn.close()
		sys.exit(0)

localcur.execute("DELETE FROM eInkDisplay")
localcur.execute("INSERT INTO eInkDisplay (CurrentCategory, CurrentTitle, LastTimeRefreshed, NextRefreshTime) VALUES (%s, %s, %s, %s)", (screen_category,screen_title,current_time,next_time))
localconn.commit()
localcur.close()
localconn.close()

epd = epd2in7b.EPD()
epd.init()

frame_black = [0] * (epd.width * epd.height / 8)
frame_red = [0] * (epd.width * epd.height / 8)

font = ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf', 18)

if os.path.isfile('/home/pi/dev/WorkPi/eink/img/' + screen_category + '_black.bmp'):
	frame_black = epd.get_frame_buffer(Image.open('/home/pi/dev/WorkPi/eink/img/' + screen_category + '_black.bmp'))
if os.path.isfile('/home/pi/dev/WorkPi/eink/img/' + screen_category + '_red.bmp'):
	frame_red = epd.get_frame_buffer(Image.open('/home/pi/dev/WorkPi/eink/img/' + screen_category + '_red.bmp'))

epd.rotate = epd2in7b.ROTATE_270
epd.width = epd2in7b.EPD_HEIGHT
epd.height = epd2in7b.EPD_WIDTH

epd.draw_filled_rectangle(frame_black, 0, 0, epd.width, 20, COLORED)
epd.draw_string_at(frame_black, 0, 0, "- WorkPi -", font, UNCOLORED)
epd.draw_string_at(frame_black, 120, 0, current_time.strftime('%y%m%d %H:%M'), font, UNCOLORED)

epd.draw_string_at(frame_black, 60, 150, screen_time, font, COLORED)

font = ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf', 12)

screen_title_lines = textwrap.wrap(screen_title,20)
if len(screen_title_lines) > 0:
	epd.draw_string_at(frame_black, 5, 30, screen_title_lines[0], font, COLORED)
if len(screen_title_lines) > 1:
	epd.draw_string_at(frame_black, 5, 50, screen_title_lines[1], font, COLORED)
if len(screen_title_lines) > 2:
	epd.draw_string_at(frame_black, 5, 70, screen_title_lines[2], font, COLORED)
if len(screen_title_lines) > 3:
	epd.draw_string_at(frame_black, 5, 90, screen_title_lines[3], font, COLORED)
if len(screen_title_lines) > 4:
	epd.draw_string_at(frame_black, 5, 110, screen_title_lines[4], font, COLORED)
if len(screen_title_lines) > 5:
	epd.draw_string_at(frame_black, 5, 130, screen_title_lines[5], font, COLORED)

epd.display_frame(frame_black, frame_red)
