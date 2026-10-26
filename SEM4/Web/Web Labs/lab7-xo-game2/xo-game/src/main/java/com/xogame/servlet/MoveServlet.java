package com.xogame.servlet;

import com.google.gson.JsonObject;
import com.xogame.exception.GameException;
import com.xogame.exception.ValidationException;
import com.xogame.model.Game;
import com.xogame.service.GameService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

@WebServlet("/app/move")
public class MoveServlet extends HttpServlet {

    private GameService gameService;

    @Override
    public void init() {
        this.gameService = ServiceLocator.getGameService();
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        resp.setContentType("application/json;charset=UTF-8");

        HttpSession session = req.getSession(false);
        int     userId = (int)    session.getAttribute("userId");
        Integer gameId = (Integer) session.getAttribute("gameId");

        if (gameId == null) {
            writeJson(resp, false, "No active game.");
            return;
        }

        int position;
        try {
            position = Integer.parseInt(req.getParameter("position"));
        } catch (NumberFormatException e) {
            writeJson(resp, false, "Invalid position.");
            return;
        }

        try {
            Game game = gameService.makeMove(gameId, userId, position);
            char winner = game.getWinner() != null ? game.getWinner() : '_';
            String msg = buildMessage(winner, game.symbolFor(userId));
            writeJson(resp, true, msg);
        } catch (ValidationException | GameException e) {
            writeJson(resp, false, e.getMessage());
        }
    }

    private String buildMessage(char result, char mySymbol) {
        if (result == mySymbol) return "You win! \uD83C\uDF89";
        if (result == 'D')     return "It's a draw!";
        if (result != '_')     return "You lose.";
        return "Move accepted.";
    }

    private void writeJson(HttpServletResponse resp, boolean success, String message)
            throws IOException {
        JsonObject obj = new JsonObject();
        obj.addProperty("success", success);
        obj.addProperty("message", message);
        resp.getWriter().write(obj.toString());
    }
}
