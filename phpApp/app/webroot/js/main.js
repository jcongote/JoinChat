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

$(window).resize(sizeContent);

function sizeContent() {
    var contentPadding = 20;

    $chat = $("#chat");
    var chatHeight;
    if ($(window).height() < 300) {
        chatHeight = $('body').height() - 2 * contentPadding - 20;
    } else {
        chatHeight = $(window).height() - 2 * contentPadding - 20;
    }
    
    $chat.css('height', chatHeight);
    $('.panel-body', $chat).css('maxHeight', chatHeight - $(".panel-heading", $chat).height() - $(".panel-footer", $chat).height() - 46);
}

function scrollDownChat() {
    $chatBody = $('#chat .panel-body');
    $chatBody.scrollTop($chatBody[0].scrollHeight);
}

var localStream, remoteStream, pc, ws, channel;
$(document).ready(function() {
    sizeContent();
    scrollDownChat();

    ws = new WebSocket('ws://' + kodingUrl + ':7000/ws/chat' + chatId);

    ws.onopen = function(){
        console.info("Websocket abierto");
    }
    ws.onclose = function (evt){
        enableChatFields(false);
        console.info("Websockect cerrado");
    }
    var initiator;
    var initiatorCall;

    $(window).bind('beforeunload', function(){
        if (pc && pc.close) {
            ws.close();
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
        $("#video-btn").prop( "disabled", true );
        var configuration = {iceServers: [
            { url: 'stun:' + kodingUrl + ':3478' },
            {
                url: 'turn:' + kodingUrl + ':3479',
                username: 'turnserver',
                credential: 'hieKedq'
            }
        ]};

        pc = new webkitRTCPeerConnection(configuration, {optional: [{RtpDataChannels: true}]});

        pc.onaddstream = function(event) {
            console.log('REMOTE STREAM ADDED');
            console.info("InitiatorCall starts the function onaddStream in: " + initiatorCall);
            remoteStream = event.stream;
            if (remoteStream) {
                $('#remote').attachStream(event.stream);
            }else{
                console.info("Initiator starts the function onaddStream in: " + initiator);
            }
            logStreaming(true);
            
            if (!localStream) {
                $('#video-btn').click();
            }
        };

        pc.onicecandidate = function(event) {
            console.info("Ice candidate added!");
            if (event.candidate) {
                ws.send(JSON.stringify(event.candidate));
            }
        };

        pc.ondatachannel = function(evt){
            channel = evt.channel;

            channel.onopen = channelOnOpen;
            channel.onmessage = channelOnMessage;
            channel.onclose = channelOnClose;
        };

        var arrayToStoreChunks = [];
        var filename;
        var receiving = false;
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

            if (objReceived.msg || objReceived.file) {
                var historyObj = historyObj = {
                    'chatId': chatId,
                    'timestamp': new Date().getTime(),
                    'received': 1
                };
            
                if (objReceived.msg) {
                    var msg = objReceived.msg;
                    console.info(evt);

                    historyObj.message = msg;
                    historyObj.type = 'message';
                    if (db) {
                        db.put('history', historyObj);
                    }
                    
                    createMsg(false, msg);
                }
                if (objReceived.file) {
                    if (!receiving) {
                        var fileBtn = $("#file-btn");
                        fileBtn.prop('disabled', true);
                        fileBtn.html('<span style="margin-left: 5px">Recibiendo...</span>');
                        receiving = true;
                    }
                    if (objReceived.file.name) {
                        filename = objReceived.file.name;
                    }
                    arrayToStoreChunks.push(objReceived.file.content);
                    if (objReceived.file.last) {
                        var fileContent = arrayToStoreChunks.join('');

                        historyObj.message = fileContent;
                        historyObj.type = 'file';
                        historyObj.filename = filename;
                        if (db) {
                            db.put('history', historyObj);
                        }

                        createFileMsg(false, fileContent, filename);

                        filename = '';
                        arrayToStoreChunks = [];
                        receiving = false;

                        var fileBtn = $("#file-btn");
                        fileBtn.prop('disabled', false);
                        fileBtn.html('');
                    }
                }
            }
        };

        var channelOnOpen = function () {
            console.log("Channel " + channel.label + " is open");
            enableChatFields(true);
        };

        var channelOnClose = function (evt) {
            console.log('RTCDataChannel closed.');
        };

        if (initiator) {
            var channelOptions = { reliable: false };
            if (typeof(channel) == "undefined" || channel == null) {
                channel = pc.createDataChannel("chat" + chatId, channelOptions);  
            };
            channel.onmessage = channelOnMessage;
            channel.onopen = channelOnOpen;
            channel.onclose = channelOnClose;
        }
        connect();
    }

    function connect(stream) {
        ws.onmessage = function (event) {
            if (event.data == "not initiator") {
                console.info(event.data);
                initiator = true;
                if (typeof(channel) != "undefined" && channel != null){
                    channel.close();
                    pc.close();
                    channel = null;
                    pc = null;
                };
                init();
            }
            else if (typeof(initiatorCall) == undefined){
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
            }
            else{
                if (initiator) {
                    $("#video-btn").prop( "disabled", false );
                };
                var signal = JSON.parse(event.data);
                if (signal.sdp) {
                    if (initiatorCall) {
                        receiveAnswer(signal);
                    } else {
                        receiveOffer(signal);
                    }
                } else if (signal.candidate) {
                    pc.addIceCandidate(new RTCIceCandidate(signal));
                }
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
        };
        if (typeof(remoteStream) == "undefined") {
            initiatorCall = false;
        };
        ws.onmessage = function (event) {
            var signal = JSON.parse(event.data);
            if (signal.sdp) {
                if (initiatorCall) {
                    receiveAnswer(signal);
                }else {
                    receiveOffer(signal);
                }
            } else if (signal.candidate) {
                pc.addIceCandidate(new RTCIceCandidate(signal));
            }
        }

        if (initiatorCall) {
            log('waiting for remote camera...');
        }else{
            console.info("crea una oferta");
            createOffer();
        }
        console.info("InitiatorCall ended the function enableVideo in: " + initiatorCall);
    }


    function createOffer() {
        log('creating offer...');
        pc.createOffer(function(offer) {
            log('created offer...');
            pc.setLocalDescription(offer, function() {
                log('sending to remote...');
                initiator = true;
                initiatorCall = true;
                console.info(offer);
                console.info("InitiatorCall was in the function createOffer: " + initiatorCall);
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
                    initiatorCall = false;
                    console.info(answer);
                    console.info("InitiatorCall was in the function receiveOffer: " + initiatorCall);
                    ws.send(JSON.stringify(answer));
                }, fail);
            }, fail);
        }, fail);
    }


    function receiveAnswer(answer) {
        log('received answer');
        console.info("InitiatorCall was in the function receiveAnswer: " + initiatorCall);
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

        if (db) {
            var historyObj = historyObj = {
                'chatId': chatId,
                'timestamp': new Date().getTime(),
                'message': objToSend.msg,
                'type': 'message',
                'received': 0
            };
            db.put('history', historyObj);
        }
            
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
        if (initiator) {
            initiator = false;
        };
        initiatorCall = true;
        console.info("InitiatorCall starts in " + initiatorCall);
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
        initiatorCall = false;
        $('#videochat').slideToggle();
        $(this).toggleClass('active');
        console.info("InitiatorCall ended the functios videoBtnClick in " + initiatorCall);
    });

    $("#file-btn").click(function(event) {
        $('#file-input').click();
    });

    var filename;
    $('#file-input').change(function(event) {
        var file = this.files[0];
        if (file) {
            filename = file.name;
            var fileBtn = $("#file-btn");
            fileBtn.prop('disabled', true);
            fileBtn.html('<span style="margin-left: 5px">Enviando...</span>');

            var reader = new window.FileReader();
            reader.readAsDataURL(file);
            reader.onload = onReadAsDataURL;
        }
    });

    var chunkLength = 1050;
    var timeoutFn;
    var currentFileText = null;
    function onReadAsDataURL(event, text) {
        var objToSend = {
            'file': {}
        };

        if (event) {
            text = event.target.result; // on first invocation
            currentFileText = text;
            objToSend.file.name = filename;
        }

        if (text.length > chunkLength) {
            objToSend.file.content = text.slice(0, chunkLength); // getting chunk using predefined chunk length
        } else {
            objToSend.file.content = text;
            objToSend.file.last = true;
        }

        channel.send(JSON.stringify(objToSend));

        var remainingDataURL = text.slice(objToSend.file.content.length);
        if (remainingDataURL.length) {
            timeoutFn = setTimeout(function () {
                onReadAsDataURL(null, remainingDataURL);
            }, 500);
        } else {
            clearTimeout(timeoutFn);
            if (db) {
                var historyObj = historyObj = {
                    'chatId': chatId,
                    'timestamp': new Date().getTime(),
                    'message': currentFileText,
                    'type': 'file',
                    'filename': filename,
                    'received': 0
                };
                db.put('history', historyObj);
            }

            createFileMsg(true, currentFileText, filename);

            filename = '';
            var fileBtn = $("#file-btn");
            fileBtn.prop('disabled', false);
            fileBtn.html('');
            $('#file-input').val('');
            currentFileText = null;
        }
    }
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

function createFileMsg(localUser, fileContent, filename, date) {
    var fn = filename || 'file';
    var msg = $("<div>");
    var fileTitle = $("<span>").html('<b>Archivo:</b> ');
    var fileLink = $("<a></a>").attr('href', fileContent)
                .attr('target', '_blank')
                .attr('download', fn)
                .html(fn);

    msg.append(fileTitle);
    msg.append(fileLink);

    createMsg(localUser, msg, false, date);
}

function createMsg(localUser, msg, escape, date) {
    var containerClass;
    if (!localUser) {
        containerClass = 'bubble-left';
    } else {
        containerClass = 'bubble-right';
    }

    if (typeof(date) === 'undefined') {
        date = new Date();
    }

    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var ampm = hours >= 12 ? 'p.m.' : 'a.m.';
    hours = (hours % 12) ? hours % 12 : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    var timeString;
    if (date.toDateString() === today.toDateString()) {
        timeString = 'Hoy, ' + strTime;
    } else if (date.toDateString() === yesterday.toDateString()) {
        timeString = 'Ayer, ' + strTime;
    } else {
        var monthNames = [
            "Ene", "Feb", "Mar",
            "Abr", "May", "Jun", "Jul",
            "Ago", "Sep", "Oct",
            "Nov", "Dic"
        ];
        var date = new Date();
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();

        timeString = strTime + ' - ' + monthNames[monthIndex] + ' ' + day + ', ' + year;
    }

    escape = (typeof(escape) === 'undefined') ? true : escape;
    if (escape) {
        msg = msg.replace(/(\r[\n]?)|(\n[\r]?)/, "<br>");
    }

    var $msgContainer = $("<div>").addClass('bubble').addClass(containerClass);
    var $timeContainer = $("<span>").addClass('time').html(timeString);
    if (jQuery.type(msg) === 'string') {
        $msgContainer.append($timeContainer).append("<div class='pointer'></div>" + msg);
    } else {
        $msgContainer.append($timeContainer).append(msg).append("<div class='pointer'></div>");
    }
    
    $("#textchat").append($msgContainer);
    scrollDownChat();
}

function enableChatFields(enabled) {
    var disabled = typeof(enabled) === 'undefined' ? false : !enabled;
    $("#msg").prop('disabled', disabled);
    $("#sendChatBtn").prop('disabled', disabled);
    $("#file-btn").prop('disabled', disabled);
}

function afterInit() {
    var maxMsgsToRetrieve = 15;
    db.from('history')
        .where('chatId', '=', chatId.toString())
        .reverse()
        .list(maxMsgsToRetrieve)
        .done(
            function(messages) {
                for (var i = messages.length - 1; i >= 0; i--) {
                    var message = messages[i];
                    if (message.type === 'message') {
                        createMsg(!message.received, message.message, true, new Date(message.timestamp));
                    } else {
                        createFileMsg(!message.received, message.message, message.filename, new Date(message.timestamp));
                    }
                };
            }
        );
}