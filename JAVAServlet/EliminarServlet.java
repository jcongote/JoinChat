package Servlet;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class EliminarServlet extends HttpServlet {

public void doPost(HttpServletRequest request, HttpServletResponse response)
                                 throws ServletException, IOException{
    response.setContentType("text/html");
    PrintWriter pw = response.getWriter();
    String connectionURL = "jdbc:mysql://localhost/JoinChat";
    Connection connection;
    try{
      String name = request.getParameter("username");
       
      Class.forName("com.mysql.jdbc.Driver");
      connection = DriverManager.getConnection(connectionURL, "root", "2403");
      PreparedStatement pst = connection.prepareStatement("delete from Usuarios where nombre=?");
      pst.setString(1,name);
 
      int i = pst.executeUpdate();
      if(i!=0){
        pw.println("<br>USUARIO ELIMINADO");          
      }
      else{
        pw.println("<br>ERROR AL ELIMINAR");
       }
    }
    catch (Exception e){
      pw.println(e);
    }
  }
}

