package com.xogame.servlet;

import com.xogame.service.GameService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

/**
 * POST /app/forfeit – user leaves the game voluntarily.
 */
@WebServlet("/app/forfeit")
public class ForfeitServlet extends HttpServlet {

    private GameService gameService;

    @Override
    public void init() {
        this.gameService = ServiceLocator.getGameService();
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        Integer gameId = (Integer) session.getAttribute("gameId");
        if (gameId != null) {
            try { gameService.forfeit(gameId); } catch (Exception ignored) {}
            session.removeAttribute("gameId");
        }
        resp.sendRedirect(req.getContextPath() + "/app/lobby");
    }
}
