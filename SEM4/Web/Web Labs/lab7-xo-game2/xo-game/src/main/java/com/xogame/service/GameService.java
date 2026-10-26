package com.xogame.service;

import com.xogame.model.Game;

public interface GameService {

    
    Game joinOrCreateGame(int userId, String username);

    Game makeMove(int gameId, int userId, int position);

    Game getGame(int gameId);

    void forfeit(int gameId);
}
