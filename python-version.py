def randomEmail():
    """
    From;
    https://github.com/jsac90/Fake-Email-Generator/blob/master/FakeRandomEmails.py
    """
    extensions = ['com','net','org','gov']
    domains = ['gmail','yahoo','comcast','verizon','charter','hotmail','outlook','frontier']
    winext = extensions[random.randint(0,len(extensions)-1)]
    windom = domains[random.randint(0,len(domains)-1)]
    acclen = random.randint(1,20)
    winacc = ''.join(random.choice(string.ascii_lowercase + string.digits) for _ in range(acclen))
    finale = winacc + "@" + windom + "." + winext
    return finale

def birdDetail(id,guid,authToken,latitude,longitude):
    headers = {
        'Authorization': "Bird "+authToken,
        'Devide-id': guid,
        'User-Agent': 'Bird/4.41.0 (co.bird.Ride; build:37; iOS 12.3.1) Alamofire/4.41.0',
        'Device-Id': guid,
        'App-Version': '4.41.0',
        'Content-Type': 'application/json',
        'Location': '{"latitude":'+latitude+',"longitude":'+longitude+',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}'
    }
    data = '{"alarm":false, "bird_id": "'+ id + '"}'
    response = requests.put('https://api.birdapp.com/bird/chirp', headers=headers, data=data)
    birdDetail = response.json()
    print(json.dumps(birdDetail, indent=4, sort_keys=True))



import uuid
import requests
import random
import string
import csv
import json
import datetime


def exportBirds(location,latitude,longitude,file):
    file.write("<h1>"+location+"</h1>\n")
    
    """
    Generate random data for the variables
    """
    email = randomEmail()
    guid = str(uuid.uuid1())

    headers = {
        'User-Agent': 'Bird/4.41.0 (co.bird.Ride; build:37; iOS 12.3.1) Alamofire/4.41.0',
        'Device-Id': guid,
        'Platform': 'ios',
        'App-Version': '4.41.0',
        'Content-Type': 'application/json'   
    }
    data = '{"email": "'+ email + '"}'
    response = requests.post('https://api.birdapp.com/user/login', headers=headers, data=data)
    auth = response.json()

    if 'token' in auth:
        authID    = auth['id']
        authToken = auth['token']
    else:
        sys.exit("Failed to get auth key.")

    #print("Authorization")
    #print("ID: "+authID)
    #print("Token: "+authToken+"\n")

    headers = {
        'Authorization': "Bird "+authToken,
        'Devide-id': guid,
        'User-Agent': 'Bird/4.41.0 (co.bird.Ride; build:37; iOS 12.3.1) Alamofire/4.41.0',
        'Device-Id': guid,
        'App-Version': '4.41.0',
        'Location': '{"latitude":'+latitude+',"longitude":'+longitude+',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}'
    }
    data = '{"email": "'+ email + '"}'
    response = requests.get('https://api.birdapp.com/bird/nearby?latitude='+latitude+'&longitude='+longitude+'&radius=1000', headers=headers, data=data)
    birds = response.json()
    birds = birds['birds']

    birdsFound = str(len(birds))

    models = {}
    for bird in birds:
        if not bird['model'] in models:
            models[bird['model']] = 1
            #birdDetail(bird['id'],guid,authToken,latitude,longitude)
        else:
            models[bird['model']] += 1

    file.write("<p><i>Found "+birdsFound+" birds: "+str(models)+"</i></p>\n")

    for model in models:
        file.write("<h2>"+model+"</h2>\n")
        for bird in birds:
            if bird['model'] == model:
                file.write('<a href="https://www.google.com/maps/search/'+str(bird['location']['latitude'])+','+str(bird['location']['longitude'])+'" target="_blank">Bird</a>'+" \n")

x=datetime.datetime.now()
filename = x.strftime("%Y-%M-%d--%H-%I-%S.html")
f= open(filename,"w+")
f.write("<!DOCTYPE html>\n")

exportBirds("Castro",'37.760822','-122.435024',f)
exportBirds("Berkeley",'37.871454','-122.260274',f)
exportBirds("Mission",'37.759663','-122.414909',f)
exportBirds("Fremont",'37.544446','-121.988112',f)
exportBirds("Lake Merritt",'37.809873','-122.261877',f)
exportBirds("Mountain View",'37.386228','-122.08439',f)
exportBirds("Palo Alto",'37.441715','-122.143124',f)
exportBirds("San Jose",'37.338049','-121.886408',f)
exportBirds("Soma",'37.809873','-122.261877',f)


f.close();
print('Done!')
