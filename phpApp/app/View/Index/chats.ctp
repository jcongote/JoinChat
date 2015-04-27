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
 
    $title = 'Mis conversaciones';
    $this->assign('title', $title);

    $this->start('script');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".chat-preview").click(function(event) {
            window.location = $(this).attr('href');
        });
    });
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

                foreach ($users as $user) :
        ?>
        <div class="col-md-3">
            <div class="panel panel-primary chat-preview" href="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'viewChat', $user['idUsuario'], $user['nickname'])); ?>">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $user['nickname']; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="empty-msgs">
                        No hay mensajes previos
                    </div>
                </div>
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