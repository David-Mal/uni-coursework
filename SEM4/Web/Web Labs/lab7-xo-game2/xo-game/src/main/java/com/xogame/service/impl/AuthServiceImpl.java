package com.xogame.service.impl;

import com.xogame.exception.AuthException;
import com.xogame.exception.ValidationException;
import com.xogame.model.User;
import com.xogame.repository.UserRepository;
import com.xogame.service.AuthService;
import com.xogame.util.PasswordUtil;


public class AuthServiceImpl implements AuthService {

    private final UserRepository userRepository;

    public AuthServiceImpl(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public User login(String username, String password) {
        validateNotBlank(username, "Username is required.");
        validateNotBlank(password, "Password is required.");

        User user = userRepository.findByUsername(username.trim())
                .orElseThrow(() -> new AuthException("Invalid username or password."));

        if (!PasswordUtil.verify(password, user.getPasswordHash(), user.getSalt())) {
            throw new AuthException("Invalid username or password.");
        }

        return user;
    }

    @Override
    public User register(String username, String password, String confirmPassword) {
        validateNotBlank(username, "Username is required.");
        validateNotBlank(password, "Password is required.");
        validateNotBlank(confirmPassword, "Please confirm your password.");

        String trimmedUsername = username.trim();

        if (trimmedUsername.length() < 3 || trimmedUsername.length() > 30) {
            throw new ValidationException("Username must be 3–30 characters.");
        }
        if (!trimmedUsername.matches("[A-Za-z0-9_]+")) {
            throw new ValidationException("Username may only contain letters, digits and underscores.");
        }
        if (password.length() < 6) {
            throw new ValidationException("Password must be at least 6 characters.");
        }
        if (!password.equals(confirmPassword)) {
            throw new ValidationException("Passwords do not match.");
        }
        if (userRepository.existsByUsername(trimmedUsername)) {
            throw new AuthException("Username '" + trimmedUsername + "' is already taken.");
        }

        String salt = PasswordUtil.generateSalt();
        String hash = PasswordUtil.hash(password, salt);

        User newUser = new User();
        newUser.setUsername(trimmedUsername);
        newUser.setPasswordHash(hash);
        newUser.setSalt(salt);

        return userRepository.save(newUser);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private void validateNotBlank(String value, String message) {
        if (value == null || value.trim().isEmpty()) {
            throw new ValidationException(message);
        }
    }
}
