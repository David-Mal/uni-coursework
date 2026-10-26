package com.xogame.servlet;

import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.xogame.exception.GameException;
import com.xogame.model.Game;
import com.xogame.service.GameService;
import com.xogame.util.ServiceLocator;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;

/**
 * GET /app/status – AJAX polling endpoint returning game state as JSON.
 */
@WebServlet("/app/status")
public class StatusServlet extends HttpServlet {

    private GameService gameService;

    @Override
    public void init() {
        this.gameService = ServiceLocator.getGameService();
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        resp.setContentType("application/json;charset=UTF-8");
        resp.setHeader("Cache-Control", "no-store");

        HttpSession session = req.getSession(false);
        int     userId = (int)    session.getAttribute("userId");
        Integer gameId = (Integer) session.getAttribute("gameId");

        if (gameId == null) {
            resp.setStatus(HttpServletResponse.SC_BAD_REQUEST);
            resp.getWriter().write("{\"error\":\"No active game\"}");
            return;
        }

        try {
            Game game     = gameService.getGame(gameId);
            char mySymbol = game.symbolFor(userId);
            resp.getWriter().write(buildJson(game, mySymbol));
        } catch (GameException e) {
            resp.setStatus(HttpServletResponse.SC_NOT_FOUND);
            resp.getWriter().write("{\"error\":\"" + e.getMessage() + "\"}");
        }
    }

    private String buildJson(Game game, char mySymbol) {
        JsonObject json = new JsonObject();
        json.addProperty("gameId",      game.getId());
        json.addProperty("status",      game.getStatus().name());
        json.addProperty("currentTurn", String.valueOf(game.getCurrentTurn()));
        json.addProperty("mySymbol",    mySymbol == 0 ? null : String.valueOf(mySymbol));
        json.addProperty("playerX",     game.getPlayerX() != null ? game.getPlayerX().getUsername() : null);
        json.addProperty("playerO",     game.getPlayerO() != null ? game.getPlayerO().getUsername() : null);
        json.addProperty("winner",      game.getWinner() != null ? String.valueOf(game.getWinner()) : null);
        json.addProperty("message",     buildMessage(game, mySymbol));

        JsonArray board = new JsonArray();
        for (char c : game.getBoardArray()) board.add(c == '_' ? "" : String.valueOf(c));
        json.add("board", board);

        return json.toString();
    }

    private String buildMessage(Game game, char mySymbol) {
        switch (game.getStatus()) {
            case waiting:  return "Waiting for an opponent to join\u2026";
            case finished:
                Character w = game.getWinner();
                if (w == null || w == 'D') return "Game over \u2013 It\u2019s a draw!";
                return w == mySymbol ? "Game over \u2013 You win! \uD83C\uDF89" : "Game over \u2013 You lose.";
            case playing:
                return game.getCurrentTurn() == mySymbol ? "Your turn" : "Waiting for opponent\u2019s move\u2026";
            default: return "";
        }
    }
}
