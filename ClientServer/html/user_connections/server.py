import asyncio

from aiohttp import web

import socketio

sio = socketio.AsyncServer(async_mode='aiohttp')
app = web.Application()
sio.attach(app)

players = []
room = 0

async def index(request):
    with open('index.html') as f:
        return web.Response(text=f.read(), content_type='text/html')

# Create a new game and notify player 1
@sio.on('createGame', namespace='/game')
async def createGame(sid, data):
    global players
    global room
    players.append(data['name'])
    print(players)
    room+=1
    sio.enter_room(sid, str(room), namespace='/game')
    await sio.emit('newGame', {'name': data['name'], 'room': str(room)}, namespace='/game')
    await sio.emit('serverResponse', {'data': data['name'] + ' created room ' + str(room)}, namespace='/game') 


# Join a game by RoomID
@sio.on('joinGame', namespace='/game')
async def joinGame(sid, data):
    print ("room#: ", data['room'])
    global players

    if (players):
        players.append(data['name'])
        print(players)
        sio.enter_room(sid, data['room'], namespace='/game')
        await sio.emit('serverResponse', {'data': data['name'] + ' entered room ' + data['room']},
                    room=data['room'], namespace='/game')
        await sio.emit('player1', {}, skip_sid=sid, namespace='/game')
        await sio.emit('player2', {'name': data['name'], 'room': data['room']}, namespace='/game')
    else:
        await sio.emit('serverResponse', {'data': 'Sorry, room is full!'}, room=sid, namespace='/game')


# Handles the turn played by either player and notifiies the other 
@sio.on('playTurn', namespace='/game')
async def playTurn(sid, data): 
    await sio.emit('turnPlayed', {
        'tile': data['tile'], 'room': data['room']
    }, room=data['room'], skip_sid=sid, namespace='/game' )

# Notify the players about the victor
@sio.on('gameEnded', namespace='/game')
async def gameEnded(sid, data):
    await sio.emit('gameEnd', data, room=data['room'], skip_sid=sid, namespace='/game' )

# Send a message to the chat when RoomID and message is specified
@sio.on('sendMessage', namespace='/game')
async def sendMessage(sid, data):
    await sio.emit('serverResponse', {'data': data['message']},
                   room=data['room'], namespace='/game')

# disconnects from server
@sio.on('disconnect request', namespace='/game')
async def disconnect_request(sid):
    await sio.disconnect(sid, namespace='/game')

# This is where you would authenticate a user! (search environ)
@sio.on('connect', namespace='/game')
async def test_connect(sid, environ):
    await sio.emit('serverResponse', {'data': 'Connected', 'count': 0}, room=sid,
                   namespace='/game')
    print('Client connected... SID: ' + sid)

# prints a disconnect message when user disconnects
@sio.on('disconnect', namespace='/game')
def test_disconnect(sid):
    #i know its hard coded, just for testing
    global players
    #if (len(players) > 0):
     #   del players[-1]
    print('Client disconnected... SID: ' + sid)

app.router.add_static('/static', 'static')
app.router.add_get('/', index)

if __name__ == '__main__':
    web.run_app(app)
