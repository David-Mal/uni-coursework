package com.xogame.exception;

/**
 * Thrown when authentication fails (bad credentials, duplicate username, etc.)
 */
public class AuthException extends RuntimeException {
    public AuthException(String message) {
        super(message);
    }
    public AuthException(String message, Throwable cause) {
        super(message, cause);
    }
}
