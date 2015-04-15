// The MIT License (MIT)

// Copyright (c) 2015 

// John Congote <jcongote@gmail.com>
// Felipe Calad
// Isabel Lozano
// Juan Diego Perez
// Joinner Ovalle

// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.

package Archivos;

import java.sql.Connection;  
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;  
  
public class Login {  
    public static Usuario1 validate(String name, String pass) {          
        Usuario1 u = null;
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
  
            pst = conn.prepareStatement("select * from Usuarios where usuario=? and password=?");  
            pst.setString(1, name);  
            pst.setString(2, pass);  
  
            rs = pst.executeQuery();
            
            if (rs.next()) {  
                String usuario = rs.getString("usuario");
                String pass2 = rs.getString("password");
                String nickname =rs.getString("nickname");
                String email =rs.getString("email");
                String status =rs.getString("status");
                u=new Usuario1(usuario, pass2, nickname, email, status);
            
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
        return u;  
    }  
}  