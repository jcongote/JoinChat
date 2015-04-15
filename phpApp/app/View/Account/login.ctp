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
 
    $title = 'Iniciar sesión';
    $this->assign('title', $title);
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">JoinChat - <?php echo $title; ?></h3>
    </div>
    <div class="panel-body">
        <?php
            echo $this->Form->create('User');
            echo $this->Form->input('username', array('label' => 'Usuario', 'class' => 'form-control', 'placeholder' => 'Ej: joinnerovalle', 'div' => array('class' => 'form-group')));
            echo $this->Form->input('userpass', array(
                    'type' => 'password',
                    'label' => 'Contraseña',
                    'class' => 'form-control',
                    'placeholder' => '*********',
                    'div' => array('class' => 'form-group'),
                    'after' => $this->Html->tag('p', $this->Html->link('¿Olvidó su clave?', array('action' => 'rememberPassword')))
                )
            );
            echo $this->Form->submit('Entrar', array('class' => 'btn btn-primary pull-right'));
            echo $this->Html->link(
                'Registrarme',
                array('action' => 'register'),
                array('class' => 'btn btn-link pull-right', 'style' => 'margin-right: 10px;')
            );
            echo $this->Html->link(
                'Entrar como invitado',
                array('action' => 'loginGuest'),
                array('class' => 'btn btn-link pull-right', 'style' => 'margin-right: 10px;')
            );
            echo $this->Form->end();
        ?>
    </div>
</div>