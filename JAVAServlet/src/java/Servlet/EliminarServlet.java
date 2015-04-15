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
      PreparedStatement pst = connection.prepareStatement("delete from Usuarios where usuario=?");
      pst.setString(1,name);
 
      int i = pst.executeUpdate();
      if(i!=0){
          String format = request.getParameter("format");
       if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", true);
                json.put("message","Usuario Eliminado");
                pw.print(json);
            }
                 
      }
      else{
         String format = request.getParameter("format");
            if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", false);
                json.put("message","Error al Eliminar");
                pw.print(json);
       }
    }
    }catch (Exception e){
      pw.println(e);
    }
    pw.close();
  }
}

