<?php
/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2015 
 * 
 * John Congote <jcongote@gmail.com>
 * Felipe Calad
 * Isabel Lozano
 * Juan Diego Perez
 * Joinner Ovalle
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
    $title = 'Conversaciones';
    $this->assign('title', $title);

    $this->start('script');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".chat-preview").click(function(event) {
            window.location = $(this).attr('href');
        });
    });

    function afterInit() {
        var maxMsgsToRetrieve = 3;
        
        $("div.chat-preview").each(function(index, chatContainer) {
            var chatId = $(chatContainer).data('chat-id');
            var bubbleChatContainer = $("div.panel-body", "div.chat-preview[data-chat-id=" + chatId + "]");
            var lastChatContainer = $("div.panel-footer", "div.chat-preview[data-chat-id=" + chatId + "]");
            db.from('history')
                .where('chatId', '=', chatId.toString())
                .reverse()
                .list(maxMsgsToRetrieve)
                .done(
                    function(messages) {
                        if (!messages.length) {
                            bubbleChatContainer.html('<div class="empty-msgs">No hay mensajes previos</div>');
                        }
                        for (var i = messages.length - 1; i >= 0; i--) {
                            var message = messages[i];
                            if (message.type === 'message') {
                                createMsg(!message.received, message.message, true, new Date(message.timestamp), bubbleChatContainer);
                            } else {
                                createFileMsg(!message.received, message.message, message.filename, new Date(message.timestamp), bubbleChatContainer);
                            }
                            if (i === 0) {
                                lastChatContainer.html('<span class="label label-info">Último mensaje ' + getTimestring(new Date(message.timestamp)) + '</span>');
                            }
                        };
                    }
                );
        });
    }

    function getTimestring(date) {
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

        return timeString;
    }

    function createFileMsg(localUser, fileContent, filename, date, chatContainer) {
        var fn = filename || 'file';
        var msg = $("<div>");
        var fileTitle = $("<span>").html('<b>Archivo:</b> ');
        var fileLink = $("<a></a>").attr('href', fileContent)
                    .attr('target', '_blank')
                    .attr('download', fn)
                    .html(fn);

        msg.append(fileTitle);
        msg.append(fileLink);

        createMsg(localUser, msg, false, date, chatContainer);
    }

    function createMsg(localUser, msg, escape, date, chatContainer) {
        var containerClass;
        if (!localUser) {
            containerClass = 'bubble-left';
        } else {
            containerClass = 'bubble-right';
        }

        escape = (typeof(escape) === 'undefined') ? true : escape;
        if (escape) {
            msg = msg.replace(/(\r[\n]?)|(\n[\r]?)/, "<br>");
        }

        var $msgContainer = $("<div>").addClass('bubble').addClass(containerClass);
        var $timeContainer = $("<span>").addClass('time').html(getTimestring(date));
        if (jQuery.type(msg) === 'string') {
            $msgContainer.append($timeContainer).append("<div class='pointer'></div>" + msg);
        } else {
            $msgContainer.append(msg.append($timeContainer)).append("<div class='pointer'></div>");
        }
        
        chatContainer.append($msgContainer);
    }
</script>
<?php
    $this->end();

    $this->start('style');
?>
<style type="text/css">
    .chat-preview {
        cursor: pointer;
    }
</style>
<?php
    $this->end();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <h1><?php echo $title; ?></h1>
        </div>
    </div>
    <div class="row">
        <?php
            if (isset($users)) :
                if (empty($users)) {
                    echo '<h4>No hay chats disponibles</h4>';
                }

                foreach ($users as $i => $user) :
                    if ($i !== 0 && $i % 4 === 0) {
                        echo "</div><div class='row'>";
                    }
                    $chatUsers = array(AuthComponent::user('idUsuario'), $user['idUsuario']);
                    sort($chatUsers);
        ?>
        <div class="col-md-3 chat">
            <div class="panel panel-primary chat-preview" href="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'viewChat', $user['idUsuario'], $user['nickname'])); ?>" data-chat-id="<?php echo implode($chatUsers); ?>">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $user['nickname']; ?></h3>
                </div>
                <div class="panel-body"></div>
                <div class="panel-footer text-right">
                    <!-- <span class="label label-info">Último mensaje 2015/01/28 01:05pm</span> -->
                </div>
            </div>
        </div>
        <?php
                endforeach;
            endif;
        ?>
        <!-- <div class="col-md-3">
            <div class="panel panel-primary chat-preview" href="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'viewChat')); ?>">
                <div class="panel-heading">
                    <h3 class="panel-title">Juan Diego Pérez</h3>
                </div>
                <div class="panel-body">
                    <div class="bubble bubble-left">
                        <div class="pointer"></div>
                        Viste lo nuevo de la documentación?<br>
                        Le trabaje bastante...
                    </div>
                    <div class="bubble bubble-right">
                        <div class="pointer"></div>
                        Ufff Juancho, está brutal!!!
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <span class="label label-info">Último mensaje 2015/01/29 02:43pm</span>
                </div>
            </div>
        </div> -->
    </div>
</div>