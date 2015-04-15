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

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Registrar</title>  
</head>  
<body>  
    <form action="RegistroServlet" method="post">  
        <fieldset style="width: 300px">  
            <legend>REGISTRO: </legend>  
            <table>  
                <tr>  
                    <td>Nombre:</td>  
                    <td><input type="text" name="username" required="required" /></td>  
                </tr>  
                <tr>  
                    <td>Contraseña:</td>  
                    <td><input type="text" name="userpass" required="required" /></td>  
                </tr>
                <tr>  
                    <td>Nickname:</td>  
                    <td><input type="text" name="nickname" required="required" /></td>  
                </tr>
                <tr>  
                    <td>Email:</td>  
                    <td><input type="text" name="email" required="required" /></td>  
                </tr>
                <input type="hidden" value="json" name="format" />
                <tr>  
                    <td><input type="submit" value="ACEPTAR" /></td>  
                </tr>  
            </table>  
        </fieldset>  
    </form>  
</body>  
</html>
