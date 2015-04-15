/**
    The MIT License (MIT)

    Copyright (c) 2015 

    John Congote <jcongote@gmail.com>
    Felipe Calad
    Isabel Lozano
    Juan Diego Perez
    Joinner Ovalle

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
*/

// var chatId = false;
var chatId = '111112';
var localStream, remoteStream, pc, ws, channel;
$(document).ready(function() {
    ws = new WebSocket('ws://kladfelipe.koding.io:7000/ws/' + chatId);
    var configuration = {iceServers: [
        { url: 'stun:kladfelipe.koding.io:3478' },
        {
            url: 'turn:kladfelipe.koding.io:3978',
            username: 'turnserver',
            credential: 'hieKedq'
        }
    ]};
    var initiator;
    pc = new webkitRTCPeerConnection(configuration, {optional: [{RtpDataChannels: true}]});

    $(window).bind('beforeunload', function(){
        if (pc && pc.close) {
            channel.close();
            pc.close();
        }
    });

    function initiatorCtrl(event) {
        console.log(event.data);
        if (event.data == "fullhouse") {
            alert("full house");
        }
        if (event.data == "initiator") {
            initiator = false;
            init();
        }
        if (event.data == "not initiator") {
            initiator = true;
            init();
        }
    }

    ws.onmessage = initiatorCtrl;

    function init() {
        var channelOnMessage = function (evt) {
            var objReceived = JSON.parse(evt.data);
            if (objReceived.command) {
                var commandParts = objReceived.command.split('.');
                var args = [];
                if (objReceived.args) {
                    args = objReceived.args;
                }
                window[commandParts[0]][commandParts[1]](args);
            }
            if (objReceived.msg) {
                var msg = objReceived.msg;
                console.info(evt);
                createMsg(false, msg);
            }  
        };
        var channelOnOpen = function () {
            console.log("Channel " + channel.label + " is open");
            enableChatFields();
        };
        var channelOnClose = function (evt) {
            console.log('RTCDataChannel closed.');
        };

        if (initiator) {
            var channelOptions = { reliable: false };
            channel = pc.createDataChannel("chat" + chatId, channelOptions);

            channel.onmessage = channelOnMessage;
            channel.onopen = channelOnOpen;
            channel.onclose = channelOnClose;
        } else {
            pc.ondatachannel = function(evt){
                channel = evt.channel;

                channel.onopen = channelOnOpen;
                channel.onmessage = channelOnMessage;
                channel.onclose = channelOnClose;
            };
        }
        connect();
    }

    function connect(stream) {
        pc.onaddstream = function(event) {
            console.log('REMOTE STREAM ADDED');
            remoteStream = event.stream;
            $('#remote').attachStream(event.stream);
            logStreaming(true);
            
            if (!localStream) {
                $('#video-btn').click();
            }
        };
        // pc.onremovestream = function(event) {
        //     $('#remote').removeStream(event.stream);
        //     logStreaming(false);
        // };

        pc.onicecandidate = function(event) {
            if (event.candidate) {
                ws.send(JSON.stringify(event.candidate));
            }
        };
        ws.onmessage = function (event) {
            var signal = JSON.parse(event.data);
            if (signal.sdp) {
                if (initiator) {
                    receiveAnswer(signal);
                } else {
                    receiveOffer(signal);
                }
            } else if (signal.candidate) {
                pc.addIceCandidate(new RTCIceCandidate(signal));
            }
        };

        if (initiator) {
            createOffer();
        } else {
            log('waiting for offer...');
        }
        logStreaming(false);
    }

    function enableVideo(stream) {
        if (stream) {
            localStream = stream;
            pc.addStream(stream);
            $('#local').attachStream(stream);
        }

        createOffer();
    }


    function createOffer() {
        log('creating offer...');
        pc.createOffer(function(offer) {
            log('created offer...');
            pc.setLocalDescription(offer, function() {
                log('sending to remote...');
                initiator = true;
                console.info(offer);
                ws.send(JSON.stringify(offer));
            }, fail);
        }, fail);
    }


    function receiveOffer(offer) {
        log('received offer...');
        pc.setRemoteDescription(new RTCSessionDescription(offer), function() {
            log('creating answer...');
            pc.createAnswer(function(answer) {
                log('created answer...');
                pc.setLocalDescription(answer, function() {
                    log('sent answer');
                    initiator = false;
                    console.info(answer);
                    ws.send(JSON.stringify(answer));
                }, fail);
            }, fail);
        }, fail);
    }


    function receiveAnswer(answer) {
        log('received answer');
        pc.setRemoteDescription(new RTCSessionDescription(answer));
    }


    function log() {
        $('#status').text(Array.prototype.join.call(arguments, ' '));
        console.log.apply(console, arguments);
    }


    function logStreaming(streaming) {
        $('#streaming').text(streaming ? '[streaming]' : '[..]');
    }


    function fail() {
        if (arguments[0].name == "PermissionDeniedError") {
            alert("No permitiste compartir multimedia");
        } else {
            $('#status').text(Array.prototype.join.call(arguments, ' '));
            $('#status').addClass('error');
            console.error.apply(console, arguments);
        }
    }

    function sendChatMessage() {
        var objToSend = {
            msg: $("#msg").val()
        };
        channel.send(JSON.stringify(objToSend));
        createMsg(true, $("#msg").val());
        $("#msg").val("");
    }

    $("#sendChatBtn").on('click', function() {
        sendChatMessage();
    });

    var keydown, lastKeydown;
    $("#msg").on('keydown', function(event) {
        if (keydown && (lastKeydown !== keydown)) {
            lastKeydown = keydown;
        } 
        keydown = event.which;
    });
    $("#msg").on('keypress', function(event) {
        // 16 - Shift
        // 13 - Enter
        if (lastKeydown !== 16 && keydown === event.which && keydown == 13) {
            sendChatMessage();
            event.preventDefault();
        }
    });

    jQuery.fn.attachStream = function(stream) {
        this.each(function() {
            this.src = URL.createObjectURL(stream);
            this.play();
        });
    };

    $('#video-btn').click(function(event) {
        if (!$(this).hasClass('active')) {
            MediaStreamTrack.getSources(function (media_sources) {
                var constraints = {};
                for (var i = 0; i < media_sources.length; i++) {
                    var media_source = media_sources[i];

                    // if audio device
                    if (media_source.kind == 'audio') {
                        constraints.audio = true;
                    }

                    // if video device
                    if (media_source.kind == 'video') {
                        constraints.video = true;
                    }
                }

                getUserMedia(constraints, enableVideo, fail);
            });
        } else {
            if (pc && pc.close) {
                pc.close();
            }
        }

        $('#videochat').slideToggle();
        $(this).toggleClass('active');
    });
});

function randomString() {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ";
    var string_length = 8;
    var randomstring = '';
    for (var i=0; i<string_length; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
    }
    return randomstring;
}

function createMsg(localUser, msg) {
    var containerClass;
    if (!localUser) {
        containerClass = 'bubble-left';
    } else {
        containerClass = 'bubble-right';
    }

    $msgContainer = $("<div>").addClass('bubble').addClass(containerClass).html("<div class='pointer'></div>" + msg.replace(/(\r[\n]?)|(\n[\r]?)/, "<br>"));
    
    $("#textchat").append($msgContainer);
}

function enableChatFields() {
    $("#msg").prop('disabled', false);
    $("#sendChatBtn").prop('disabled', false);
}