package Archivos;

import java.sql.Connection;  
import java.sql.DriverManager;  
import java.sql.PreparedStatement;  
import java.sql.ResultSet;  
import java.sql.SQLException;  
import java.util.ArrayList;
  
public class RegresarUsuarios {  
    public static ArrayList validate(String username) {          
        Usuario1 u = null;
        ArrayList<Usuario1> UsuariosList = new ArrayList<Usuario1>();
        Connection conn = null;  
        PreparedStatement pst = null;  
        ResultSet rs = null;  
        
        String url = "jdbc:mysql://localhost/";  
        String dbName = "JoinChat";  
        String driver = "com.mysql.jdbc.Driver";  
        String userName = "root";  
        String password = "2403"; 

        try {  
            Class.forName(driver).newInstance();  
            conn = DriverManager.getConnection(url + dbName, userName, password);  
  
            pst = conn.prepareStatement("SELECT * FROM Usuarios WHERE  usuario != ?");  
            pst.setString(1, username);    
  
            rs = pst.executeQuery();
            
            while (rs.next()) {  
                String idUsuario = rs.getString("idUsuario");
                String usuario = rs.getString("usuario");
                String pass2 = rs.getString("password");
                String nickname =rs.getString("nickname");
                String email =rs.getString("email");
                String status =rs.getString("status");
                u=new Usuario1(idUsuario,usuario, pass2, nickname, email, status);
                UsuariosList.add(u);
            
            }
        } catch (Exception e) {  
            System.out.println(e);  
        } finally {  
            if (conn != null) {  
                try {  
                    conn.close();  
                } catch (SQLException e) {  
                    e.printStackTrace();  
                }  
            }  
            if (pst != null) {  
                try {  
                    pst.close();  
                } catch (SQLException e) {  
                    e.printStackTrace();  
                }  
            }  
            if (rs != null) {  
                try {  
                    rs.close();  
                } catch (SQLException e) {  
                    e.printStackTrace();  
                }  
            }  
        }  
        return UsuariosList;  
    }  
}  