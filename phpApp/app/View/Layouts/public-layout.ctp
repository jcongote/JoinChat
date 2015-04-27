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
                'styles-public.css'
            ));
        ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
            <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
        <![endif]-->
        <?php echo $this->fetch('css'); ?>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="centering col-xs-5">
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
                    <div class="alert alert-dismissible alert-warning">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?php echo $authMsg; ?>
                    </div>
                    <?php
                        endif;
                        echo $this->fetch('content');
                    ?>
                </div>
            </div>
        </div>
        <?php
            echo $this->Html->script('jquery-1.11.2.min');
            echo $this->Html->script('bootstrap.min');

            echo $this->element('localDb');
            echo $this->fetch('script');
        ?>
    </body>
</html>