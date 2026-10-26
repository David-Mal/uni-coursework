package com.xogame.service;

import com.xogame.model.User;

public interface AuthService {

    User login(String username, String password);

    User register(String username, String password, String confirmPassword);
}
