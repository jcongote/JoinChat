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
    $title = 'Editar Perfil';
    $this->assign('title', $title);

    $this->start('script');

    echo $this->Html->script('jquery.validate.min');
    echo $this->Html->script('localization/jquery-validate-es.min');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#UserEditForm").validate({
            rules: {
                "data[User][nickname]": {
                    required: true,
                    minlength: 3
                },
                "data[User][email]": {
                    required: true,
                    email: true
                }
            },
            submitHandler: function(form) {
                $(form).find('input[type=submit]').prop('disabled', true);
                form.submit();
            }
        });
    });
</script>
<?php $this->end(); ?>
<h3><?php echo $title; ?></h3>
<?php
    echo $this->Form->create('User');
    echo $this->Form->input('nickname', array(
            'label' => 'Alias',
            'class' => 'form-control',
            'placeholder' => 'Ej: Joi',
            'div' => array('class' => 'form-group'),
            'after' => $this->Html->para('help-block', 'Será el apodo con el que las demás personas te reconoceran')
        )
    );
    echo $this->Form->input('email', array('label' => 'Correo', 'class' => 'form-control', 'placeholder' => 'Ej: joinner@gmail.com', 'div' => array('class' => 'form-group')));
    echo $this->Form->submit('Enviar', array('class' => 'btn btn-primary pull-right'));
    echo $this->Form->end();
?>