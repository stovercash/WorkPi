import json
import sys
import icalendar
from datetime import datetime, date, time, timedelta
import pytz
import pymysql

import epd2in7b
import Image
import ImageFont
import ImageDraw

mysecrets = json.loads(open("/home/pi/dev/WorkPi/setup/secrets.json").read())
localconn = pymysql.connect("localhost",mysecrets["sql"]["user"],mysecrets["sql"]["pass"],"")
localcur = localconn.cursor()

COLORED = 1
UNCOLORED = 0

current_time = datetime.now(pytz.timezone("EST"))

screen_category = ""
screen_title = ""
screen_time = ""

file = open("../cal/calendar.ics", "rb")
ical = icalendar.Calendar.from_ical(file.read())
for event in ical.walk("VEVENT"):
	if (event.get("X-MICROSOFT-CDO-ALLDAYEVENT") == "FALSE") and (event.get("X-MICROSOFT-CDO-IMPORTANCE") != "0"):
		event_dtstart = event.get("DTSTART")
		event_dtend = event.get("DTEND")
		start_time = event_dtstart.dt - timedelta(minutes=5)
		end_time = event_dtend.dt
		#if (end_time > current_time):
		if (start_time < current_time) AND (end_time > current_time):
			screen_title = event.get("SUMMARY")
			screen_title = screen_title[:100]
			screen_category = "MEETING"
			screen_time = event_dtstart.dt.strftime("%-I:%M %p") + " - " + event_dtend.dt.strftime("%-I:%M %p")
file.close()

current_time = datetime.now()

#if 1==1:
if screen_category == "":
	if (current_time.time() > time(8,0)) and (current_time.time() < time(9,0)):
		screen_title = "Good Morning"
		screen_category = "MORNING"
		screen_time = ""
	if (current_time.time() > time(12,0)) and (current_time.time() < time(14,0)):
		screen_title = "Eat Lunch"
		screen_category = "LUNCH"
		screen_time = ""
	if (current_time.time() > time(18,0)) and (current_time.time() < time(19,0)):
		screen_title = "Home"
		screen_category = "NIGHT"
		screen_time = ""

if screen_category == "":
	screen_title = ""
	screen_category = "BLANK"
	screen_time = ""

#print(screen_title + " - " + screen_category + " - " + screen_time)

localcur.execute("USE " + mysecrets["sql"]["dbname"] + ";")
localcur.execute("SELECT CurrentCategory, CurrentTitle FROM eInkDisplay")

if localcur.rowcount > 0:
	sqlDisp = localcur.fetchone()
	if (sqlDisp[0] == screen_category) and (sqlDisp[1] == screen_title):
		sys.exit(0)

localcur.execute("DELETE FROM eInkDisplay")
localcur.execute("INSERT INTO eInkDisplay (CurrentCategory, CurrentTitle, LastTimeRefreshed) VALUES (%s, %s, %s)", (screen_category,screen_title,current_time))
localconn.commit()
localcur.close()
localconn.close()

epd = epd2in7b.EPD()
epd.init()
epd.rotate = epd2in7b.ROTATE_270
epd.width = epd2in7b.EPD_HEIGHT
epd.height = epd2in7b.EPD_WIDTH

frame_black = [0] * (epd.width * epd.height / 8)
frame_red = [0] * (epd.width * epd.height / 8)

font = ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf', 18)

if os.path.isfile('img/' + screen_category + '_black.bmp'):
	frame_black = epd.get_frame_buffer(Image.open('img/' + screen_category + '_black.bmp'))
if os.path.isfile('img/' + screen_category + '_red.bmp'):
	frame_red = epd.get_frame_buffer(Image.open('img/' + screen_category + '_red.bmp'))

epd.draw_filled_rectangle(frame_red, 0, 0, epd.width, 20, COLORED)
epd.draw_string_at(frame_red, 0, 0, "- WorkPi -", font, UNCOLORED)
epd.draw_string_at(frame_red, 120, 0, current_time.strftime('%y%m%d %H:%M'), font, UNCOLORED)

epd.draw_string_at(frame_black, 120, 150, screen_time, font, COLORED)

font = ImageFont.truetype('/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf', 12)
epd.draw_string_at(frame_black, 0, 30, screen_title[:19], font, COLORED)
epd.draw_string_at(frame_black, 0, 50, screen_time[20:39], font, COLORED)
epd.draw_string_at(frame_black, 0, 70, screen_time[40:59], font, COLORED)
epd.draw_string_at(frame_black, 0, 90, screen_time[60:79], font, COLORED)
epd.draw_string_at(frame_black, 0, 110, screen_time[80:100], font, COLORED)

eod.display_frame(frame_black, frame_red)

#print("Refresh screen")
