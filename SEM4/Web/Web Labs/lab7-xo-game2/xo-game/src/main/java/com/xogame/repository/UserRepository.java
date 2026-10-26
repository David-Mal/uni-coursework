package com.xogame.repository;

import com.xogame.model.User;
import java.util.Optional;

public interface UserRepository {

    /** Find a user by their username. Returns empty if not found. */
    Optional<User> findByUsername(String username);

    /** Find a user by their primary key. Returns empty if not found. */
    Optional<User> findById(int id);

    /**
     * Persist a new user.
     * @return the saved User with its generated id populated.
     * @throws com.xogame.exception.RepositoryException if username already exists.
     */
    User save(User user);

    /** Returns true if the given username is already taken. */
    boolean existsByUsername(String username);
}
