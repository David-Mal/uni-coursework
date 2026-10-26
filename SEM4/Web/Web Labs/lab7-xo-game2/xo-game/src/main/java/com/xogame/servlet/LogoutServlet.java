package com.xogame.servlet;

import com.xogame.service.GameService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

/**
 * GET /logout – forfeits active game, destroys session, redirects to login.
 */
@WebServlet("/logout")
public class LogoutServlet extends HttpServlet {

    private GameService gameService;

    @Override
    public void init() {
        this.gameService = ServiceLocator.getGameService();
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session != null) {
            Integer gameId = (Integer) session.getAttribute("gameId");
            if (gameId != null) {
                try { gameService.forfeit(gameId); } catch (Exception ignored) {}
            }
            session.invalidate();
        }
        resp.sendRedirect(req.getContextPath() + "/login");
    }
}
