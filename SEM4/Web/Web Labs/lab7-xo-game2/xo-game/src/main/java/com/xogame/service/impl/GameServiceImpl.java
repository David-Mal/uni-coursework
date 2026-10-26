package com.xogame.service.impl;

import com.xogame.exception.GameException;
import com.xogame.exception.ValidationException;
import com.xogame.model.Game;
import com.xogame.model.User;
import com.xogame.repository.GameRepository;
import com.xogame.service.GameService;

public class GameServiceImpl implements GameService {

    private final GameRepository gameRepository;

    public GameServiceImpl(GameRepository gameRepository) {
        this.gameRepository = gameRepository;
    }

    @Override
    public Game joinOrCreateGame(int userId, String username) {
        return gameRepository.findActiveGameForUser(userId).orElseGet(() -> {
            return gameRepository.findActiveGame().map(activeGame -> {
                if (activeGame.getStatus() == Game.Status.waiting) {
                    gameRepository.joinGame(activeGame.getId(), userId);
                    return gameRepository.findById(activeGame.getId())
                            .orElseThrow(() -> new GameException("Game disappeared after joining."));
                } else {
                    throw new GameException("A game is already in progress. Please wait.");
                }
            }).orElseGet(() -> {
                User playerX = new User(userId, username);
                Game newGame = new Game();
                newGame.setPlayerX(playerX);
                return gameRepository.save(newGame);
            });
        });
    }

    @Override
    public Game makeMove(int gameId, int userId, int position) {
        if (position < 0 || position > 8) {
            throw new ValidationException("Position must be between 0 and 8.");
        }
        if (position == 0) {
            throw new ValidationException("Top-left corner is forbidden.");
        }

        Game game = gameRepository.findById(gameId)
                .orElseThrow(() -> new GameException("Game not found."));

        if (game.getStatus() != Game.Status.playing) {
            throw new GameException("The game is not currently in progress.");
        }

        char mySymbol = game.symbolFor(userId);
        if (mySymbol == 0) {
            throw new GameException("You are not a player in this game.");
        }
        if (game.getCurrentTurn() != mySymbol) {
            throw new GameException("It is not your turn.");
        }
        if (game.cellAt(position) != '_') {
            throw new GameException("That cell is already taken.");
        }

        game.applyMove(position, mySymbol);
        gameRepository.update(game);

        return game;
    }

    @Override
    public Game getGame(int gameId) {
        return gameRepository.findById(gameId)
                .orElseThrow(() -> new GameException("Game not found."));
    }

    @Override
    public void forfeit(int gameId) {
        gameRepository.forfeit(gameId);
    }
}
