package com.xogame.servlet;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

/**
 * POST /app/play-again – clears gameId from session, sends user back to lobby.
 */
@WebServlet("/app/play-again")
public class PlayAgainServlet extends HttpServlet {

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session = req.getSession(false);
        if (session != null) session.removeAttribute("gameId");
        resp.sendRedirect(req.getContextPath() + "/app/lobby");
    }
}
