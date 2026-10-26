package com.xogame.servlet;

import com.xogame.exception.AuthException;
import com.xogame.exception.ValidationException;
import com.xogame.model.User;
import com.xogame.service.AuthService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

/**
 * Handles GET /login  → show login/register form
 *         POST /login → authenticate or register
 *
 * Single Responsibility: HTTP request/response handling only.
 * All business logic is delegated to {@link AuthService}.
 */
@WebServlet(urlPatterns = {"/login", "/register"})
public class LoginServlet extends HttpServlet {

    private AuthService authService;

    @Override
    public void init() {
        this.authService = ServiceLocator.getAuthService();
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session != null && session.getAttribute("userId") != null) {
            resp.sendRedirect(req.getContextPath() + "/app/lobby");
            return;
        }
        String path = req.getServletPath();
        req.setAttribute("activeTab", "/register".equals(path) ? "register" : "login");
        req.getRequestDispatcher("/login.jsp").forward(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        String action   = req.getParameter("action");
        String username = req.getParameter("username");
        String password = req.getParameter("password");

        try {
            User user;
            if ("register".equals(action)) {
                String confirm = req.getParameter("confirmPassword");
                user = authService.register(username, password, confirm);
                req.setAttribute("activeTab", "register");
            } else {
                user = authService.login(username, password);
                req.setAttribute("activeTab", "login");
            }
            createSession(req, user);
            resp.sendRedirect(req.getContextPath() + "/app/lobby");

        } catch (ValidationException | AuthException e) {
            req.setAttribute("activeTab", "register".equals(action) ? "register" : "login");
            req.setAttribute("errorMsg", e.getMessage());
            req.getRequestDispatcher("/login.jsp").forward(req, resp);
        }
    }

    private void createSession(HttpServletRequest req, User user) {
        HttpSession old = req.getSession(false);
        if (old != null) old.invalidate();
        HttpSession session = req.getSession(true);
        session.setAttribute("userId",   user.getId());
        session.setAttribute("username", user.getUsername());
        session.setMaxInactiveInterval(30 * 60);
    }
}
