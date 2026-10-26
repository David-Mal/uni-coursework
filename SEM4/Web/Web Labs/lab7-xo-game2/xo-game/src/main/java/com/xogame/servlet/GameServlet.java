package com.xogame.servlet;

import com.xogame.exception.GameException;
import com.xogame.model.Game;
import com.xogame.service.GameService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

/**
 * GET /app/game – loads game state and forwards to game.jsp.
 */
@WebServlet("/app/game")
public class GameServlet extends HttpServlet {

    private GameService gameService;

    @Override
    public void init() {
        this.gameService = ServiceLocator.getGameService();
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        int     userId = (int) session.getAttribute("userId");
        Integer gameId = (Integer) session.getAttribute("gameId");

        if (gameId == null) {
            resp.sendRedirect(req.getContextPath() + "/app/lobby");
            return;
        }

        try {
            Game game     = gameService.getGame(gameId);
            char mySymbol = game.symbolFor(userId);
            req.setAttribute("game",     game);
            req.setAttribute("mySymbol", mySymbol);
            req.getRequestDispatcher("/WEB-INF/jsp/game.jsp").forward(req, resp);
        } catch (GameException e) {
            session.removeAttribute("gameId");
            resp.sendRedirect(req.getContextPath() + "/app/lobby");
        }
    }
}
