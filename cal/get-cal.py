from requests import get

with open("calendar.ics", "wb") as file:
	response = get("https://outlook.office365.com/owa/calendar/1758ed2d5d7846f8a1708841edc2f2f8@3plsoftware.com/dbec0bac4ea944fbb94f143db95c899b10470443457261369769/calendar.ics")
	file.write(response.content)
