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

    $title = 'Cambiar clave';
    $this->assign('title', $title);

    $this->start('script');

    echo $this->Html->script('jquery.validate.min');
    echo $this->Html->script('localization/jquery-validate-es.min');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#UserChangePasswordForm").validate({
            rules: {
                "data[User][oldPassword]": {
                    required: true,
                    maxlength: 10
                },
                "data[User][password]": {
                    required: true,
                    maxlength: 10
                },
                "data[User][passwordConfirm]": {
                    equalTo: "#UserPassword",
                    maxlength: 10
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
    echo $this->Form->input('oldPassword', array(
            'type' => 'password',
            'label' => 'Clave actual',
            'class' => 'form-control',
            'div' => array('class' => 'form-group')
        )
    );
    echo $this->Form->input('password', array(
            'type' => 'password',
            'label' => 'Nueva clave',
            'class' => 'form-control',
            'div' => array('class' => 'form-group')
        )
    );
    echo $this->Form->input('passwordConfirm', array(
            'type' => 'password',
            'label' => 'Confirmación de nueva clave',
            'class' => 'form-control',
            'div' => array('class' => 'form-group')
        )
    );
    echo $this->Form->submit('Enviar', array('class' => 'btn btn-primary pull-right'));
    echo $this->Form->end();
?>