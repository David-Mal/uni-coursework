package com.xogame.servlet;

import com.xogame.exception.GameException;
import com.xogame.model.Game;
import com.xogame.service.GameService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

@WebServlet("/app/lobby")
public class LobbyServlet extends HttpServlet {

    private GameService gameService;

    @Override
    public void init() {
        this.gameService = ServiceLocator.getGameService();
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session  = req.getSession(false);
        int    userId   = (int)    session.getAttribute("userId");
        String username = (String) session.getAttribute("username");

        try {
            Game game = gameService.joinOrCreateGame(userId, username);
            session.setAttribute("gameId", game.getId());
            resp.sendRedirect(req.getContextPath() + "/app/game");
        } catch (GameException e) {
            req.setAttribute("errorMsg", e.getMessage());
            req.setAttribute("username", username);
            req.getRequestDispatcher("/WEB-INF/jsp/lobby-full.jsp").forward(req, resp);
        }
    }
}
