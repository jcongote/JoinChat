<!-- The MIT License (MIT)

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
THE SOFTWARE. -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title><?php echo $this->fetch('title'); ?> | JoinChat</title>
        <?php
            echo $this->Html->css(array(
                'bootstrap.theme.min',
                'styles-private'
            ));
        ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
            <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
        <![endif]-->
        <?php echo $this->fetch('style'); ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="sidebar-wrapper">
                <?php 
                    $links = array(
                        'index' => array('class' => '', 'html' => ''),
                        'newchat' => array('class' => '', 'html' => ''),
                        'chats' => array('class' => '', 'html' => ''),
                        'settings' => array('class' => '', 'html' => ''),
                        'register' => array('class' => '', 'html' => '')
                    );
                    
                    $links[$activeOption]['class'] = 'active';

                    $guest = false;
                    if (AuthComponent::user('guest') && AuthComponent::user('guest') === true) {
                        $guest = true;
                        $links['register']['html'] = '<li>' . $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-log-in btn btn-primary')) . $this->Html->tag('span', 'Registrarme'),
                            '/account/register',
                            array('escape' => false, 'class' => $links['register']['class'])
                        ) . '</li>';
                    }

                    if ($guest) {
                        $links['newchat']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-plus btn btn-primary')) . $this->Html->tag('span', 'Nueva conversación'),
                            '#',
                            array('escape' => false, 'class' => $links['newchat']['class'], 'data-toggle' => 'modal', 'data-target' => '#guest-modal')
                        );
                        $links['chats']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-comment btn btn-primary')) . $this->Html->tag('span', 'Mis conversaciones'),
                            '#',
                            array('escape' => false, 'class' => $links['chats']['class'], 'data-toggle' => 'modal', 'data-target' => '#guest-modal')
                        );
                        $links['settings']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-wrench btn btn-primary')) . $this->Html->tag('span', 'Configuración'),
                            '#',
                            array('escape' => false, 'class' => $links['settings']['class'], 'data-toggle' => 'modal', 'data-target' => '#guest-modal')
                        );
                    } else {
                        $links['index']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-home btn btn-primary')) . $this->Html->tag('span', 'Inicio'),
                            '/index',
                            array('escape' => false, 'class' => $links['index']['class'])
                        );
                        $links['newchat']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-plus btn btn-primary')) . $this->Html->tag('span', 'Nueva conversación'),
                            '#',
                            array('escape' => false, 'class' => $links['newchat']['class'], 'data-toggle' => 'modal', 'data-target' => '#new-chat-modal')
                        );
                        $links['chats']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-comment btn btn-primary')) . $this->Html->tag('span', 'Mis conversaciones'),
                            '/index/chats',
                            array('escape' => false, 'class' => $links['chats']['class'])
                        );
                        $links['settings']['html'] = $this->Html->link(
                            $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-wrench btn btn-primary')) . $this->Html->tag('span', 'Configuración'),
                            '/account/settings',
                            array('escape' => false, 'class' => $links['settings']['class'])
                        );
                    }

                    echo <<<NAV
                        <ul class='sidebar-nav'>
                            <li class='sidebar-brand'>
                                <span class='brand-title'>
                                    JoinChat
                                </span>
                                <span class='helper-brand'>JC</span>
                                <a href="#" class="btn btn-default" id="menu-toggle"><span class="icon glyphicon glyphicon-menu-hamburger"></span></a>
                            </li>
                            <li>
                                {$links['index']['html']}
                            </li>
                            {$links['register']['html']}
                            <li>
                                {$links['newchat']['html']}
                            </li>
                            <li>
                                {$links['chats']['html']}
                            </li>
                            <li>
                                {$links['settings']['html']}
                            </li>
                            <li>
                                {$this->Html->link(
                                    $this->Html->tag('span', '', array('class' => 'icon glyphicon glyphicon-off btn btn-primary')) . $this->Html->tag('span', 'Cerrar sesión'),
                                    array('controller' => 'Account', 'action' => 'logout'),
                                    array('escape' => false)
                                )}
                            </li>
                        </ul>
NAV;
                ?>
            </div>

            <div id="page-content-wrapper">
                <?php 
                    $flashMsg = $this->Session->flash();
                    $authMsg = $this->Session->flash('auth');
                    $warnMsg = $this->Session->flash('warning');

                    if ($flashMsg) :
                ?>
                <div class="alert alert-dismissible alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $flashMsg; ?>
                </div>
                <?php 
                    endif;

                    if ($warnMsg) :
                ?>
                <div class="alert alert-dismissible alert-warning">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $warnMsg; ?>
                </div>
                <?php 
                    endif;

                    if ($authMsg) :
                ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $authMsg; ?>
                </div>
                <?php
                    endif;
                    echo $this->fetch('content');
                ?>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="new-chat-modal" tabindex="-1" role="dialog" aria-labelledby="new-chat-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Contactos</h4>
                    </div>
                    <div class="modal-body">
                        <?php
                            // echo $this->Form->create($model = 'Contact', $options = array('type' => 'get', 'url' => array('controller' => 'contacts', 'action' => 'search')));
                            echo $this->Form->input('nicknameEmail', array(
                                    'type' => 'text',
                                    'label' => 'Alias / Correo',
                                    'class' => 'form-control',
                                    'div' => array('class' => 'form-group')
                                )
                            );
                            // echo $this->Form->end($options = null);
                        ?>
                        <div class="list-group">
                            <a href="#" class="list-group-item">Contacto 1</a>
                            <a href="#" class="list-group-item">Contacto 2</a>
                            <a href="#" class="list-group-item">Contacto 3</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <ul id="chat-participants" class="list-inline pull-left">
                            <li class="contact-label">
                                <span class="contact-remove">&times;</span>
                                Default
                            </li>
                            <li class="contact-label">
                                <span class="contact-remove">&times;</span>
                                Contacto 5
                            </li>
                            <li class="contact-label">
                                <span class="contact-remove">&times;</span>
                                Default
                            </li>
                            <li class="contact-label">
                                <span class="contact-remove">&times;</span>
                                Contacto 5
                            </li>
                            <li class="contact-label">
                                <span class="contact-remove">&times;</span>
                                Default
                            </li>
                            <li class="contact-label">
                                <span class="contact-remove">&times;</span>
                                Contacto 5
                            </li>
                        </ul>
                        <button type="button" class="btn btn-primary">Empezar chat</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="guest-modal" tabindex="-1" role="dialog" aria-labelledby="guest-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">¡Aún eres un invitado!</h4>
                    </div>
                    <div class="modal-body">
                        <p>Para poder acceder a este link debes registrarte primero. Te invitamos a que te registres en este <?php echo $this->Html->link(
                            'link',
                            '/account/register',
                            array('class' => $links['register']['class'])
                        ) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
            echo $this->Html->script('jquery-1.11.2.min');
            echo $this->Html->script('bootstrap.min');
            
            echo $this->element('localDb');
        ?>
        <script type='text/javascript'>
            $('#menu-toggle').click(function(e) {
                e.preventDefault();
                $('#wrapper').toggleClass('toggled');
            });
            $('.contact-remove').click(function(e) {
                $(this).parent().fadeOut(200, function() {
                    $(this).remove();
                });
            });
        </script>
        <?php echo $this->fetch('script'); ?>
    </body>
</html>