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
 
    $title = 'Chat con ' . $nickname;
    $this->assign('title', $title);

    $users = array(AuthComponent::user('idUsuario'), $idUser);
    sort($users);
?>
<?php $this->start('script'); ?>
<script type="text/javascript">
    window.kodingUrl = '<?php echo Configure::read(APPLICATION_ENV . ".kodingUrl"); ?>';
    var chatId = '<?php echo implode($users); ?>';

    $(document).ready(function() {
        sizeContent();
        $chatBody = $('#chat .panel-body');
        $chatBody.scrollTop($chatBody[0].scrollHeight);
    });

    $(window).resize(sizeContent);

    function sizeContent() {
        var contentPadding = 20;

        $chat = $("#chat");
        var chatHeight = $(window).height() - 2 * contentPadding - 20;
        $chat.css('height', chatHeight);
        $('.panel-body', $chat).css('maxHeight', chatHeight - $(".panel-heading", $chat).height() - $(".panel-footer", $chat).height() - 46);
    }
</script>
<?php
    echo $this->Html->script('webrtc-adapter.js');
    echo $this->Html->script('main.js');
    $this->end();

    $this->start('style');
?>
<style type="text/css">
    #videochat {
        border-bottom: 4px solid #e7e7e7;
        margin-bottom: 10px;
        background-color: #eee;
        margin-top: -15px;
        padding-top: 5px;
        display: none;
    }

    #videochat video {
        max-width: 300px;
        max-height: 200px;
    }
</style>
<?php
    $this->end();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div id="chat" class="panel panel-primary" style="position: relative;">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $nickname; ?><!-- <span class="label label-info pull-right">Último mensaje 2015/01/28 01:05pm</span> --></h3>
                </div>
                <div class="panel-body" style="overflow-y: auto;">
                    <div id="videochat" class="row">
                        <div class="col-md-6 text-center">
                            <video id="local" autoplay muted controls>
                                Tu cámara...
                            </video>
                        </div>
                        <div class="col-md-6 text-center">
                            <video id="remote" autoplay controls>
                                Cámara de <?php echo $nickname; ?>
                            </video>
                        </div>
                        <div id="video-id" class="col-xs-12 text-center"></div>
                        <div id="status" style="display: block;" class="col-xs-12"></div>
                        <div id="streaming" style="display: block;" class="col-xs-12"></div>
                    </div>
                    <div id="textchat"></div>
                </div>
                <div class="panel-footer text-right" style="position: absolute; bottom: 0; width: 100%;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <button id="video-btn" class="pull-left btn btn-success btn-xs glyphicon glyphicon-facetime-video"></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <textarea id="msg" class="form-control" disabled="disabled"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button id="sendChatBtn" class="btn btn-success" disabled="disabled">Enviar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>