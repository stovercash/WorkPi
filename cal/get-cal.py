import json
from requests import get

mysecrets = json.loads(open("/home/pi/dev/WorkPi/setup/secrets.json").read())

with open("/home/pi/dev/WorkPi/cal/calendar.ics", "wb") as file:
	response = get(mysecrets["cal"]["ical_url"])
	file.write(response.content)
