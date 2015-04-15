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

public class RegistroServlet extends HttpServlet {

public void doPost(HttpServletRequest request, HttpServletResponse response)
                                 throws ServletException, IOException{
    response.setContentType("text/html");
    PrintWriter pw = response.getWriter();
    String connectionURL = "jdbc:mysql://localhost/JoinChat";
    Connection connection;
    try{
      String name = request.getParameter("username");
      String pass = request.getParameter("userpass");
      String nick = request.getParameter("nickname");
      String email = request.getParameter("email");
       
      Class.forName("com.mysql.jdbc.Driver");
      connection = DriverManager.getConnection(connectionURL, "root", "2403");
      PreparedStatement pst = connection.prepareStatement("insert into Usuarios (usuario, password, nickname, email) values(?,?,?,?)");
      pst.setString(1,name);
      pst.setString(2,pass);      
      pst.setString(3,nick);
      pst.setString(4,email);
 
      int i = pst.executeUpdate();
      if(i!=0){
       String format = request.getParameter("format");
       if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", true);
                json.put("message","Usuario Registrado");
                pw.print(json);
            }
          
               
      }
      else{
        String format = request.getParameter("format");
            if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", false);
                json.put("message","Error al Regristrar");
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

