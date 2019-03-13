import os 
dir_path = os.path.dirname(os.path.realpath(__file__)) + "/"
import json
from pprint import pprint
import random

#create a newWords array with changed words
#############################
def getBoard(fileName):
    with open(fileName, "r") as myfile:
        data = json.loads(myfile.read())
    board = data["board"]
    pieces = ["pieces"]
    turn = ["turn"]
    
    return board
"""
    temp = board
    for i in temp:
        for x in i:
            if x[2] == "":
                x[2] = " "
                

    for i in temp:
        for x in i:
            print(x[2], end = " ")
        print()
"""
    


    
def detectChanges(old, new):
    newWordLocation = []
    for i in range(len(old)):
        for j in range(len(old[i])):
            oldVal = old[i][j][2]
            newVal = new[i][j][2]
            if oldVal != newVal:
                #print(newVal)
                tempArr = [i, j]
                newWordLocation.append(tempArr)

    return newWordLocation




def detectHorizontalL(board, h, w, wordLocation):
    if board[h][w][2] == "":
        return
    else:
        tempArr = [h, w]
        if tempArr not in wordLocation:
            wordLocation.append(tempArr)
        if len(board[0]) - w >0 or len(board[0]) -w <14:
            detectHorizontalL(board, h, w - 1, wordLocation)
        else:
            return

def detectHorizontalR(board, h, w, wordLocation):
    if board[h][w][2] == "":
        return
    else:
        tempArr = [h, w]
        if tempArr not in wordLocation:
            wordLocation.append(tempArr)
        #need to add this in to everything else
        if len(board[0]) - w >0 or len(board[0]) -w <14:
            detectHorizontalR(board, h, w + 1, wordLocation)
        else:
            return



def detectVerticalU(board, h, w, wordLocation):
    if board[h][w][2] == "":
        return
    else:
        tempArr = [h, w]
        if tempArr not in wordLocation:
            wordLocation.append(tempArr)
        if len(board[0][0]) - h >0 or len(board[0]) -h <14:
            detectVerticalU(board, h + 1, w, wordLocation)
        else:
            return
        #detectVertical(board, h - 1, w, wordLocation)

        
def detectVerticalD(board, h, w, wordLocation):
    if board[h][w][2] == "":
        return
    else:
        tempArr = [h, w]
        if tempArr not in wordLocation:
            wordLocation.append(tempArr)
        if len(board[0][0]) - h >0 or len(board[0]) -h <14:
        #detectVertical(board, h + 1, w, wordLocation)
            detectVerticalD(board, h - 1, w, wordLocation)
        else:
            return
        
def detectVertical(board, h, w, wordLocation):
    detectVerticalU(board, h, w, wordLocation)
    detectVerticalD(board, h, w, wordLocation)

def detectHorizontal(board, h, w, wordLocation):
    detectHorizontalL(board, h, w, wordLocation)
    detectHorizontalR(board, h, w, wordLocation)





def determineScore(coordArr, board):
    marker = 0
    total = 0
    for coord in coordArr:
        temp = board[coord[0]][coord[1]]
        tempT = int(temp[0])
        
        if temp[1] == "TWS":
            marker +=3
        elif temp[1] == "DWS":
            marker +=2
        elif temp[1] == "TLS":
            tempT = int(temp[0])*3
        elif temp[1] == "DLS":
            tempT = int(temp[0])*2
        total += tempT
        board[coord[0]][coord[1]][1] == "0"
    if marker == 0:
        marker +=1
    #print(total)
    total = total*marker
    return total

        



def detectWordsCreated(wordL, board):
    #print(wordL)
    newWordArr = []
    word = ""
    for arr in wordL:
        word += board[arr[0]][arr[1]][2]
    #print(word)
    newWordArr.append(word)

    
    
    wordLocation = []
    wordVertical = []
    wordHorizontal = []
    #finds the letters in vertical
    if len(wordL) > 1:
        if wordL[0][1] - wordL[1][1] != 0:
                for coord in wordL:
                    tempArr = []
                    detectVertical(board, coord[0], coord[1], tempArr)
                    if len(tempArr) > 1:
                        wordVertical.append(tempArr)
                
        else:
            tempArr = []
            temp = wordL[0]
            detectVertical(board, temp[0], temp[1], tempArr)
            if len(tempArr) > 1:
                wordVertical.append(tempArr)




        #finds the letters in horizontal
        #print(wordL)
        if wordL[0][0] - wordL[1][0] != 0:
            for coord in wordL:
                tempArr = []
                detectHorizontal(board, coord[0], coord[1], tempArr)
                #print(tempArr)
                if len(tempArr) > 1:
                    wordHorizontal.append(tempArr)
                
        else:
            tempArr = []
            temp = wordL[0]
            detectHorizontal(board, temp[0], temp[1], tempArr)
            #print(tempArr)
            if len(tempArr) > 1:
                wordHorizontal.append(tempArr)
    else:
        tempArr = []
        temp = wordL[0]
        detectHorizontal(board, temp[0], temp[1], tempArr)
        if len(tempArr) > 1:
            wordHorizontal.append(tempArr)
        tempArr = []
        temp = wordL[0]
        detectVertical(board, temp[0], temp[1], tempArr)
        if len(tempArr) > 1:
            wordVertical.append(tempArr)

    wordLocation.append(wordHorizontal)
    wordLocation.append(wordVertical)
    #makes sure word is in order
    #wordLocation = sorted(wordLocation, key=lambda x: x[0])
    #print(wordLocation)
    wordArray = []
    scoreArray = []
    for i, direction in enumerate(wordLocation):
        if i == 1:
            for wordL in direction:
                word = ""
                wordL = sorted(wordL, key=lambda x: x[0])
                for coord in wordL:
                    word += board[coord[0]][coord[1]][2]
                score = determineScore(wordL, board)
                wordArray.append(word)
                scoreArray.append(score)
        else:
            for wordL in direction:
                word = ""
                wordL = sorted(wordL, key=lambda x: x[1])
                for coord in wordL:
                    word += board[coord[0]][coord[1]][2]
                score = determineScore(wordL, board)
                wordArray.append(word)
                scoreArray.append(score)

    for i in range(len(board)):
        for j in range(len(board[i])):
            if board[i][j][2] != "":
                board[i][j][1] = "0"

               
    #for word in wordArray:
    #    print(word)
    #for score in scoreArray:
    #    print(score)

    totalArray = []
    totalArray.append(wordArray)
    totalArray.append(scoreArray)

    
    return totalArray
        
    
    
def returnJson(board, score_words):
    score = score_words[1]
    words = score_words[0]

    dicti = {}
    dicti["board"] = board
    dicti["score"] = score
    dicti["words"] = words

    print(json.dumps(dicti))

        
#def determinPointValue(word):
        #generate a key


#############################
fileName = dir_path + "temp.json"
newBoard = getBoard(fileName)
fileName = dir_path + "old.json"
oldBoard = getBoard(fileName)

newWordLocation = detectChanges(oldBoard, newBoard)

if len(newWordLocation) != 0:
    words = detectWordsCreated(newWordLocation, newBoard)
else:
    words = []
    words.append("0")
    words.append("0")

returnJson(newBoard, words)



#############################
"""

for i in letterBag:
    print(i)

temp = board
for i in temp:
    for x in i:
        if x[1] == 0:
            x[1] = "   "
            

for i in temp:
    for x in i:
        print(x[1], end = " ")
    print()
"""
##############################
