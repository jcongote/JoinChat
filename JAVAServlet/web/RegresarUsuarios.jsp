<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Buscar</title>  
</head>  
<body>  
    <form action="RegresarUsuariosServlet" method="post">  
        <fieldset style="width: 300px">  
            <legend>REGRESAR USUARIOS: </legend>  
            <table>  
                <tr>  
                    <td>Usuario:</td>  
                    <td><input type="text" name="username" required="required" /></td>  
                </tr> 
                <input type="hidden" value="json" name="format" />
                <tr>  
                    <td><input type="submit" value="Resultado" /></td>  
                </tr>  
            </table>  
        </fieldset>  
    </form>  
</body>  
</html>