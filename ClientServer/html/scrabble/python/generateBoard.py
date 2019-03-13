
import json
from pprint import pprint
import random
import os 
dir_path = os.path.dirname(os.path.realpath(__file__))

def createBoard(height, width):
    #think of creating a 3D board in order to save numbers for starting points
    #Board = [[[0, ""]]*width]*height
    Board = []
    for i in range(height):
        temp1 = []
        for x in range(width):
            tempArr = ["0", "0", ""]
            temp1.append(tempArr)
        Board.append(temp1)
    return Board



def insertBonus(arrX, arrY, i, j, value):
    for k in arrX:
        if k == i:  
            for l in arrY:
                if l == j:
                    board[i][j][1] = value


def addBonus(board):
    #Triple Word Score
    TWSArrXY = [0,7,14]

    #Double Letter Score
    DLS0X = [3, 11]
    DLS2X = [6,8]
    DLS3X = [0,7,14]
    DLS6X = [2,6,8,12]
    DLS0Y = [0,7,14]
    DLS2Y = [2, 12]
    DLS3Y = [3, 11]
    DLS6Y = [6, 8]


    #Double Word Score
    DWS1X = [1,13]
    DWS2X = [2,12]
    DWS3X = [3,11]
    DWS4X = [4,10]
    DWS1Y = [1,13]
    DWS2Y = [2,12]
    DWS3Y = [3,11]
    DWS4Y = [4,10]

    #Triple Letter Score
    TLS1X = [5, 9]
    TLS5X = [1, 5, 9, 13]
    TLS1Y = [1, 13]
    TLS5Y = [5, 9]
    

    for i in range(len(board)):
        for j in range(len(board)):
            insertBonus(TWSArrXY, TWSArrXY, i, j, "TWS")
            
            insertBonus(DLS2X, DLS2Y, i, j, "DLS")
            insertBonus(DLS3X, DLS3Y, i, j, "DLS")
            insertBonus(DLS6X, DLS6Y, i, j, "DLS")
            insertBonus(DLS0X, DLS0Y, i, j, "DLS")
            
            insertBonus(DWS1X, DWS1Y, i, j, "DWS")
            insertBonus(DWS2X, DWS2Y, i, j, "DWS")
            insertBonus(DWS3X, DWS3Y, i, j, "DWS")
            insertBonus(DWS4X, DWS4Y, i, j, "DWS")

            insertBonus(TLS1X, TLS1Y, i, j, "TLS")
            insertBonus(TLS5X, TLS5Y, i, j, "TLS")

            
    board[7][7][1] = "DWS"

    return board



    
def getData(fileName, numberWords, maxLength):
    #chooses words needed randomly
    with open(fileName, "r", encoding='utf-8') as myfile:
        data = json.load(myfile)

    #puts keys into array so that it can be randomly chosen from
    keyArr = []
    for key in data.keys():
        #makes sure that keys are not numbers and that they do not have spaces
        if not key.isdigit() and " " not in key and len(key) <= maxLength:
            keyArr.append(key)

            
    #puts chosen words into an array
    wordArr = []
    for i in range(numberWords):
        choice = random.choice(keyArr)
        #ensures that key and definition are passed, otherwise only definition is passed
        tempArr = [choice, data[choice]]
        
        wordArr.append(tempArr)
    
    return wordArr

def putInBag(letter, number, bag):
    for i in range(number):
        bag.append(letter)

def generatePieces():
    #Letter, pointValue
    #need to pop off if letter is taken from bag
    letterBag = []
    pointArr = []
    Point0 = ["-"]
    Point1 = ["E", "A", "I", "O", "N", "R", "T", "L", "S", "U"]
    Point2 = ["D", "G"]
    Point3 = ["B", "C", "M", "P"]
    Point4 = ["F", "H", "V", "W", "Y"]
    Point5 = ["K"]
    Point8 = ["J", "X"]
    Point10 = ["Q", "Z"]

    for letter in Point0:
        tempArr = [letter, 0]
        putInBag(tempArr, 2, letterBag)
    
    for letter in Point1:
        tempArr = []
        if letter == "A" or letter == "I":
            tempArr = [letter, 1]
            putInBag(tempArr, 9, letterBag)
        elif letter == "E":
            tempArr = [letter, 1]
            putInBag(tempArr, 12, letterBag)
        elif letter == "O":
            tempArr = [letter, 1]
            putInBag(tempArr, 8, letterBag)
        elif letter in "NRT":
            tempArr = [letter, 1]
            putInBag(tempArr, 6, letterBag)
        elif letter in "LSU":
            tempArr = [letter, 1]
            putInBag(tempArr, 4, letterBag)
            

    for letter in Point2:
        tempArr = []
        if letter == "G":
            tempArr = [letter, 2]
            putInBag(tempArr, 3, letterBag)
        if letter == "D":
            tempArr = [letter, 2]
            putInBag(tempArr, 4, letterBag)

    for letter in Point3:
        tempArr = [letter, 3]
        putInBag(tempArr, 2, letterBag)
        
    for letter in Point4:
        tempArr = [letter, 4]
        putInBag(tempArr, 2, letterBag)

    for letter in Point5:
        tempArr = [letter, 5]
        putInBag(tempArr, 1, letterBag)

    for letter in Point8:
        tempArr = [letter, 8]
        putInBag(tempArr, 1, letterBag)

    for letter in Point10:
        tempArr = [letter, 10]
        putInBag(tempArr, 1, letterBag)


    return letterBag

#############################

#length, width
#
#file, how many, max length
#words = getData("wordsapi_sample.json", 10, 60)

#place(words, board)



board = createBoard(15,15)
board = addBonus(board)
letterBag = generatePieces()


dictS = {}
dictS["board"] = board
dictS["pieces"] = letterBag
dictS["turn"] = 0
print(json.dumps(dictS))


with open(dir_path + "old.json", "w") as myfile:
    myfile.write(json.dumps(dictS))

#############################

"""
for i in letterBag:
    print(i)

temp = board
for i in temp:
    for x in i:
        if x[1] == "0":
            x[1] = "   "
            

for i in temp:
    for x in i:
        print(x[1], end = " ")
    print()
"""
##############################
