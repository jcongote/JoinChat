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
import org.json.simple.JSONObject;

public class ActualizarPasswordServlet extends HttpServlet {

public void doPost(HttpServletRequest request, HttpServletResponse response)
                                 throws ServletException, IOException{
    response.setContentType("text/html");
    PrintWriter pw = response.getWriter();
    String connectionURL = "jdbc:mysql://localhost/JoinChat";
    Connection connection;
    try{
      String name = request.getParameter("username");
      String pass = request.getParameter("userpass");

       
      Class.forName("com.mysql.jdbc.Driver");
      connection = DriverManager.getConnection(connectionURL, "root", "2403");
      
      
      PreparedStatement pst = connection.prepareStatement("update Usuarios set password=? where usuario=?");
      pst.setString(1,pass);
      pst.setString(2,name);

     
 
      int i = pst.executeUpdate();
      if(i!=0){
          String format = request.getParameter("format");
       if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", true);
                json.put("message","Datos Actualizados");
                pw.print(json);
            }
                 
      }
      else{
        String format = request.getParameter("format");
            if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", false);
                json.put("message","Error al actualizar");
                pw.print(json);
       }
       }
    
      
    }
    catch (Exception e){
      pw.println(e);
    }
    pw.close();
  }
}