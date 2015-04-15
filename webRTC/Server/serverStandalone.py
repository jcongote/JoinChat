# The MIT License (MIT)

# Copyright (c) 2015 

# John Congote <jcongote@gmail.com>
# Felipe Calad
# Isabel Lozano
# Juan Diego Perez
# Joinner Ovalle

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.
import logging
from tornado.ioloop import IOLoop
from tornado.web import RequestHandler, Application, url
from tornado.websocket import WebSocketHandler
from tornado.httpserver import HTTPServer
import uuid

global_rooms = {}
class Room(object):
    def __init__(self, name, clients=[]):
        print(name)
        self.name = name
        self.clients = clients
    def __repr__(self):
        return self.name

class HelloHandler(RequestHandler):
    def get(self):
        room = str(uuid.uuid4())
        self.write(room)

class RegisterHandler(RequestHandler):
    def get(self,idChat=1,idUser=1):
        print("get headers: ", self._headers)
        self.set_header("Access-Control-Allow-Origin","*")
        objectInit = {"idUser":idUser,"state":"initiator"}
        objectNotInit = {"idUser":idUser,"state":"not initiator"}
        if idChat in global_rooms:
            if len(global_rooms[idChat].clients) < 2:
                if len(global_rooms[idChat].clients) == 0:
                    global_rooms[idChat].clients.append(objectInit)
                elif len(global_rooms[idChat].clients) == 1 and global_rooms[idChat].clients[0]["state"] == "initiator":
                    indexClient = 1
                    msg = "not initiator"
                    global_rooms[idChat].clients.append(objectNotInit)
                else:
                    indexClient = 1
                    msg = "not initiator"
                    global_rooms[idChat].clients.append(objectNotInit)
            else:
                self.write("fullhouse")
        else:
            global_rooms[idChat] = Room(idChat,objectInit)
        print("User and chat created",idUser,"--",idChat)
        
class NewWS(WebSocketHandler):
    def open(self,slug):
        self.msg = "initiator"
        self.i = 1
        self.roomId = slug
        self.indexClient = 0
        objectInit = {"instance":self,"state":"initiator"}
        objectNotInit = {"instance":self,"state":"not initiator"}
        if self.roomId in global_rooms:
            if len(global_rooms[self.roomId].clients) < 2:
                print("Cantidad de clientes",len(global_rooms[self.roomId].clients))
                if len(global_rooms[self.roomId].clients) == 0:
                    global_rooms[self.roomId].clients.append(objectInit)
                else:
                    global_rooms[self.roomId].clients.append(objectInit)
            else:
                self.msg = "fullhouse"
        else:
            global_rooms[self.roomId] = Room(self.roomId,[objectInit])
            
        print("Mensaje: ",self.msg)
        self.write_message(self.msg)
        msgToOthers =""
        index = 0
        if self.msg == "initiator":
            msgToOthers ="not initiator"
        else:
            msgToOthers ="initiator"
        if self.msg != "fullhouse":
            for client in global_rooms[self.roomId].clients:
                if client["instance"] is self:
                    continue
                print("Mensaje para los otros",msgToOthers)
                client["instance"].write_message(msgToOthers)
                index = global_rooms[self.roomId].clients.index(client)
                global_rooms[self.roomId].clients[index]["state"] = msgToOthers
        self.room = global_rooms[self.roomId]
        print("Client: ", self.room.clients)
        print("WebSocket opened")
        
    def on_message(self, message):
        print("Enviando mensaje el cliente: ", self.room.clients)
        for client in global_rooms[self.roomId].clients:
            if client["instance"] is self:
                continue
            client["instance"].write_message(message)

    def on_close(self):
        for client in global_rooms[self.roomId].clients:
            if client["instance"] is self:
                self.room.clients.remove(client)
                break
        print("Client: ", self.room.clients)
        print("WebSocket closed")
    
    def check_origin(self, origin):
        return True
        
    
def make_app():
    return Application([
        url(r"/", HelloHandler),
        url(r"/sign/([^/]*)/([^/]*)", RegisterHandler),
        # url(r"/([^/]*)", HelloHandler),
        url(r'/ws/([^/]*)', NewWS),
        # url(r'/ws/([^/]*)', EchoWebSocket)
        ])

def main():
    app = make_app()
    http_server = HTTPServer(app)
    http_server.listen(7000)
    print("Running in the port 7000")
    IOLoop.current().start()
    
if __name__ == '__main__':
    main()

