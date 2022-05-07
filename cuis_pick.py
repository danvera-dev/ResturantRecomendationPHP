#!/usr/bin/python3
print("Content-type:text/html\r\n\r\n")
import random
import db_config

db = db_config.db
mycursor = db.cursor()
user_id = 1

mycursor.execute('select * from 2022S_CPS3961_01.Cuisine_pref where user_id = 1')   #gets cuisine ratings from user_id i, 1 is a placeholedr
i, j = (1, 0)
cuis_id = list();   #list for cuisine_id
cuis_rating = list();   #list for cuisine_rating
for row in mycursor:
    while i < len(row):
        if(row[i] != 0):    #checks if rating is above 0
            cuis_id.insert(j, i)
            cuis_rating.insert(j, row[i])
            j+=1
        i+=1
print(cuis_id)
print(cuis_rating)
randomList = random.choices(cuis_id, cuis_rating, k = 1)    #Uses weighted random to pick the cuisine id
mycursor.execute('select cuisine_type from 2022S_CPS3961_01.Cuisine_list where cuisine_id = (%s)',(randomList)) #gets the cusine name that corresponds with cuisine id
for x in mycursor:
    cuis_choice = x[0]
print(f"<br><b>{cuis_choice}</b>")
print(f"<br>User_id: {user_id}")
mycursor.execute('select * from 2022S_CPS3961_01.Cuisine_list')
cuis_list = list()
i = 0
for x in mycursor:
    if(i >= len(cuis_id)):
        break
    if(x[0] == cuis_id[i]):
        cuis_list.insert(i, x[1])
        i+=1
sum = 0
for x in cuis_rating:
    sum += x
cuis_perc = list()
i = 0
for x in cuis_rating:
    perc = (x*100)/sum
    perc = str(round(perc, 2))
    perc += '%'
    cuis_perc.insert(i, (cuis_list[i], perc))
    i+=1
print(cuis_perc)



