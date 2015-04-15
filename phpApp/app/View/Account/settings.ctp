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

    $this->assign('title', 'Configuración');

    $user = AuthComponent::user();
?>
<h3>Mi Perfil</h3>
<div class="list-group">
    <div class="list-group-item">
        <h4 class="list-group-item-heading">Alias (Apodo)</h4>
        <div class="list-group-item-text"><?php echo $user['nickname'] ?></div>
    </div>
    <div class="list-group-item">
        <h4 class="list-group-item-heading">Usuario</h4>
        <div class="list-group-item-text"><?php echo $user['usuario'] ?></div>
    </div>
    <div class="list-group-item">
        <h4 class="list-group-item-heading">Correo</h4>
        <div class="list-group-item-text"><?php echo $user['email'] ?></div>
    </div>
</div>
<?php
    echo $this->Html->link('Editar', array('action' => 'edit'), array('class' => 'btn btn-link'));
    echo $this->Html->link('Cambiar clave', array('action' => 'changePassword'), array('class' => 'btn btn-link'));
?>