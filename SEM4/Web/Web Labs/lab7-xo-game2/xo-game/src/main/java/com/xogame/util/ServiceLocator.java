package com.xogame.util;

import com.xogame.repository.GameRepository;
import com.xogame.repository.UserRepository;
import com.xogame.repository.impl.SqliteGameRepository;
import com.xogame.repository.impl.SqliteUserRepository;
import com.xogame.service.AuthService;
import com.xogame.service.GameService;
import com.xogame.service.impl.AuthServiceImpl;
import com.xogame.service.impl.GameServiceImpl;


public final class ServiceLocator {

    private static final UserRepository USER_REPOSITORY =
            new SqliteUserRepository();

    private static final GameRepository GAME_REPOSITORY =
            new SqliteGameRepository(USER_REPOSITORY);

    private static final AuthService AUTH_SERVICE =
            new AuthServiceImpl(USER_REPOSITORY);

    private static final GameService GAME_SERVICE =
            new GameServiceImpl(GAME_REPOSITORY);

    private ServiceLocator() {}

    public static AuthService getAuthService() { return AUTH_SERVICE; }
    public static GameService getGameService() { return GAME_SERVICE; }
    public static UserRepository getUserRepository() { return USER_REPOSITORY; }
    public static GameRepository getGameRepository() { return GAME_REPOSITORY; }
}
