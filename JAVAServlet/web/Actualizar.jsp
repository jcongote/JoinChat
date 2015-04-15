<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Actualizar Nickname</title>  
</head>  
<body>  
    <form action="ActualizarServlet" method="post">  
        <fieldset style="width: 300px">  
            <legend>ACTUALIZAR NICKNAME y EMAIL DEL USUARIO: </legend>  
            <table>  
                <tr>  
                    <td>Usuario:</td>  
                    <td><input type="text" name="username" required="required" /></td>  
                </tr>
                <tr>  
                    <td>Nuevo NickName:</td>  
                    <td><input type="text" name="nickname" required="required" /></td>  
                </tr>
                <tr>  
                    <td>Nuevo Email:</td>  
                    <td><input type="text" name="email" required="required" /></td>  
                </tr>
                <input type="hidden" value="json" name="format" />
                <tr>  
                    <td><input type="submit" value="Actualizar" /></td>  
                </tr>  
            </table>  
        </fieldset>  
    </form>  
</body>  
</html>