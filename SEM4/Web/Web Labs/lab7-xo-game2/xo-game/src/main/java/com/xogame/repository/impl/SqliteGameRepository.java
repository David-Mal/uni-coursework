package com.xogame.repository.impl;

import com.xogame.exception.RepositoryException;
import com.xogame.model.Game;
import com.xogame.model.User;
import com.xogame.repository.GameRepository;
import com.xogame.repository.UserRepository;
import com.xogame.util.DBUtil;

import java.sql.*;
import java.util.Optional;

public class SqliteGameRepository implements GameRepository {

    private final UserRepository userRepository;

    public SqliteGameRepository(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public Optional<Game> findById(int gameId) {
        String sql = "SELECT id, player_x_id, player_o_id, board, current_turn, status, winner " +
                     "FROM games WHERE id = ?";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setInt(1, gameId);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) return Optional.of(mapRow(rs));
            }
        } catch (SQLException e) {
            throw new RepositoryException("findById failed for gameId: " + gameId, e);
        }
        return Optional.empty();
    }

    @Override
    public Optional<Game> findActiveGame() {
        String sql = "SELECT id, player_x_id, player_o_id, board, current_turn, status, winner " +
                     "FROM games WHERE status IN ('waiting','playing') ORDER BY id DESC LIMIT 1";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            if (rs.next()) return Optional.of(mapRow(rs));
        } catch (SQLException e) {
            throw new RepositoryException("findActiveGame failed", e);
        }
        return Optional.empty();
    }

    @Override
    public Optional<Game> findActiveGameForUser(int userId) {
        String sql = "SELECT id, player_x_id, player_o_id, board, current_turn, status, winner " +
                     "FROM games WHERE (player_x_id = ? OR player_o_id = ?) " +
                     "AND status IN ('waiting','playing') ORDER BY id DESC LIMIT 1";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setInt(1, userId);
            ps.setInt(2, userId);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) return Optional.of(mapRow(rs));
            }
        } catch (SQLException e) {
            throw new RepositoryException("findActiveGameForUser failed for userId: " + userId, e);
        }
        return Optional.empty();
    }

    @Override
    public Game save(Game game) {
        String sql = "INSERT INTO games (player_x_id, board, current_turn, status) VALUES (?, ?, ?, ?)";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            ps.setInt(1, game.getPlayerX().getId());
            ps.setString(2, game.getBoard());
            ps.setString(3, String.valueOf(game.getCurrentTurn()));
            ps.setString(4, game.getStatus().name());
            ps.executeUpdate();
            try (ResultSet keys = ps.getGeneratedKeys()) {
                if (keys.next()) {
                    game.setId(keys.getInt(1));
                    return game;
                }
            }
        } catch (SQLException e) {
            throw new RepositoryException("save game failed", e);
        }
        throw new RepositoryException("No generated key returned after INSERT", null);
    }

    @Override
    public void update(Game game) {
        String sql = "UPDATE games SET board = ?, current_turn = ?, status = ?, winner = ?, " +
                     "updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setString(1, game.getBoard());
            ps.setString(2, String.valueOf(game.getCurrentTurn()));
            ps.setString(3, game.getStatus().name());
            if (game.getWinner() != null) {
                ps.setString(4, String.valueOf(game.getWinner()));
            } else {
                ps.setNull(4, Types.VARCHAR);
            }
            ps.setInt(5, game.getId());
            ps.executeUpdate();
        } catch (SQLException e) {
            throw new RepositoryException("update game failed for gameId: " + game.getId(), e);
        }
    }

    @Override
    public void joinGame(int gameId, int playerOId) {
        String sql = "UPDATE games SET player_o_id = ?, status = 'playing', " +
                     "updated_at = CURRENT_TIMESTAMP WHERE id = ? AND status = 'waiting'";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setInt(1, playerOId);
            ps.setInt(2, gameId);
            int rows = ps.executeUpdate();
            if (rows == 0) {
                throw new RepositoryException("joinGame failed – game " + gameId + " is no longer waiting", null);
            }
        } catch (SQLException e) {
            throw new RepositoryException("joinGame failed for gameId: " + gameId, e);
        }
    }

    @Override
    public void forfeit(int gameId) {
        String sql = "UPDATE games SET status = 'finished', updated_at = CURRENT_TIMESTAMP " +
                     "WHERE id = ? AND status IN ('waiting','playing')";
        try (Connection conn = DBUtil.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql)) {
            ps.setInt(1, gameId);
            ps.executeUpdate();
        } catch (SQLException e) {
            throw new RepositoryException("forfeit failed for gameId: " + gameId, e);
        }
    }

    // ── Mapping ──────────────────────────────────────────────────────────────

    private Game mapRow(ResultSet rs) throws SQLException {
        Game g = new Game();
        g.setId(rs.getInt("id"));
        g.setBoard(rs.getString("board"));

        String ct = rs.getString("current_turn");
        g.setCurrentTurn(ct != null ? ct.charAt(0) : 'X');
        g.setStatus(Game.Status.valueOf(rs.getString("status")));

        String w = rs.getString("winner");
        g.setWinner(w != null ? w.charAt(0) : null);

        int xId = rs.getInt("player_x_id");
        if (!rs.wasNull()) {
            userRepository.findById(xId).ifPresent(g::setPlayerX);
        }

        int oId = rs.getInt("player_o_id");
        if (!rs.wasNull()) {
            userRepository.findById(oId).ifPresent(g::setPlayerO);
        }

        return g;
    }
}
