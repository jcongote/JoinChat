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

package Servlet;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import Archivos.Login;
import Archivos.Usuario1;
import org.json.simple.*;

public class LoginServlet extends HttpServlet {

    private static final long serialVersionUID = 1L;

    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String n = request.getParameter("username");
        String p = request.getParameter("userpass");

        Usuario1 u = Login.validate(n, p);
        if (u!=null) {
            String format = request.getParameter("format");
            
            if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", true);
                JSONObject user = new JSONObject();
                user.put("usuario", u.getUsuario());
                user.put("password", u.getPassword());
                user.put("nickname",u.getNickname());
                user.put("email", u.getEmail());
                user.put("status",u.getStatus());
                json.put("user", user);                
                out.print(json);
            }
        } else {
            String format = request.getParameter("format");
            if (format.equals("json")) {
                JSONObject json = new JSONObject();
                json.put("success", false);
                json.put("message","Usuario no registrado");
                out.print(json);
               
                
            }

        }

        out.close();
    }
}
