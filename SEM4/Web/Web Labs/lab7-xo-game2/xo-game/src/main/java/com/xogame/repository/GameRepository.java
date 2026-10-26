package com.xogame.repository;

import com.xogame.model.Game;
import java.util.Optional;

public interface GameRepository {

    Optional<Game> findById(int gameId);

    
    Optional<Game> findActiveGame();


    Optional<Game> findActiveGameForUser(int userId);

    Game save(Game game);

    void update(Game game);

    void joinGame(int gameId, int playerOId);

    void forfeit(int gameId);
}
