package com.xogame.util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

public final class DBUtil {

    private static final String DB_FILE = System.getProperty("user.home") + "/xogame.db";
    private static final String URL = "jdbc:sqlite:" + DB_FILE;

    static {
        try {
            Class.forName("org.sqlite.JDBC");
            initSchema();
        } catch (ClassNotFoundException | SQLException e) {
            throw new ExceptionInInitializerError(e);
        }
    }

    private DBUtil() {}

    public static Connection getConnection() throws SQLException {
        Connection conn = DriverManager.getConnection(URL);

        try (Statement st = conn.createStatement()) {
            st.execute("PRAGMA journal_mode=WAL");
            st.execute("PRAGMA foreign_keys=ON");
        }
        return conn;
    }

    private static void initSchema() throws SQLException {
        try (Connection conn = DriverManager.getConnection(URL);
             Statement st = conn.createStatement()) {

            st.execute("PRAGMA foreign_keys=ON");

            // Users table
            st.execute("""
                CREATE TABLE IF NOT EXISTS users (
                    id       INTEGER PRIMARY KEY AUTOINCREMENT,
                    username VARCHAR(50) NOT NULL UNIQUE,
                    password_hash VARCHAR(128) NOT NULL,
                    salt          VARCHAR(64)  NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            """);

            st.execute("""
                CREATE TABLE IF NOT EXISTS games (
                    id           INTEGER PRIMARY KEY AUTOINCREMENT,
                    player_x_id  INTEGER REFERENCES users(id),
                    player_o_id  INTEGER REFERENCES users(id),
                    board        VARCHAR(9) NOT NULL DEFAULT '_________',
                    current_turn CHAR(1)    NOT NULL DEFAULT 'X',
                    status       VARCHAR(10) NOT NULL DEFAULT 'waiting',
                    winner       CHAR(1),
                    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            """);

            // Seed two demo accounts if the table is empty
            st.execute("""
                INSERT OR IGNORE INTO users (username, password_hash, salt)
                VALUES ('player1',
                        'fa59a8551d0bc72a9602df1d970a2a5bec7c16fc36bdabd5e7377a6205268f10',
                        '__demo__'),
                       ('player2',
                        'fa59a8551d0bc72a9602df1d970a2a5bec7c16fc36bdabd5e7377a6205268f10',
                        '__demo__')
            """);
        }
    }
}
