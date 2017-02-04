import json
import requests

mysecrets = json.loads(open("../../../Documents/secrets.json").read())
user = mysecrets["secrets"]["vso"]["user"]
pat = mysecrets["secrets"]["vso"]["pat"]
baseurl = mysecrets["secrets"]["vso"]["baseurl"]

restfunc = 'DefaultCollection/_apis/tfvc/changesets?fromDate=02-01-2017&toDate=02-04-2017&api-version=2.0'

results = requests.get(baseurl+restfunc, auth=(user,pat))
print(results.json())
