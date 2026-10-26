package com.xogame.model;

/**
 * Represents one game of X-O.
 *
 * Board is a 9-character String indexed 0-8:
 *   0 | 1 | 2
 *   ---------
 *   3 | 4 | 5
 *   ---------
 *   6 | 7 | 8
 *
 * Cell value: '_' = empty, 'X' = X player, 'O' = O player
 */
public class Game {

    public enum Status { waiting, playing, finished }

    private static final int[][] WIN_LINES = {
        {0,1,2}, {3,4,5}, {6,7,8},   // rows
        {0,3,6}, {1,4,7}, {2,5,8},   // cols
        {0,4,8}, {2,4,6}             // diagonals
    };

    private int    id;
    private User   playerX;
    private User   playerO;
    private String board;        // 9 chars
    private char   currentTurn;  // 'X' or 'O'
    private Status status;
    private Character winner;    // 'X', 'O', 'D'(raw) or null

    public Game() {
        this.board       = "_________";
        this.currentTurn = 'X';
        this.status      = Status.waiting;
    }

    // ---- board helpers -------------------------------------------------------

    public char[] getBoardArray() {
        return board.toCharArray();
    }

    public char cellAt(int pos) {
        return board.charAt(pos);
    }

    
    public char applyMove(int pos, char symbol) {
        if (pos < 0 || pos > 8) throw new IllegalArgumentException("Position out of range");
        if (board.charAt(pos) != '_') throw new IllegalStateException("Cell already occupied");
        if (symbol != currentTurn) throw new IllegalStateException("Not this player's turn");

        char[] cells = board.toCharArray();
        cells[pos] = symbol;
        board = new String(cells);

        char result = checkWinner();
        if (result != '_') {
            status = Status.finished;
            winner = (result == 'D') ? 'D' : result;
        } else {
            currentTurn = (currentTurn == 'X') ? 'O' : 'X';
        }
        return result;
    }

    /**
     * Returns 'X' or 'O' if that symbol wins, 'D' for draw, '_' if game continues.
     */
    public char checkWinner() {
        for (int[] line : WIN_LINES) {
            char c = board.charAt(line[0]);
            if (c != '_' && c == board.charAt(line[1]) && c == board.charAt(line[2])) {
                return c;
            }
        }
        if (!board.contains("_")) return 'D';
        return '_';
    }

    // ---- getters / setters ---------------------------------------------------

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public User getPlayerX() { return playerX; }
    public void setPlayerX(User playerX) { this.playerX = playerX; }

    public User getPlayerO() { return playerO; }
    public void setPlayerO(User playerO) { this.playerO = playerO; }

    public String getBoard() { return board; }
    public void setBoard(String board) { this.board = board; }

    public char getCurrentTurn() { return currentTurn; }
    public void setCurrentTurn(char currentTurn) { this.currentTurn = currentTurn; }

    public Status getStatus() { return status; }
    public void setStatus(Status status) { this.status = status; }

    public Character getWinner() { return winner; }
    public void setWinner(Character winner) { this.winner = winner; }

    /**
     * Returns the symbol ('X' or 'O') assigned to the given user, or 0 if they are not in the game.
     */
    public char symbolFor(int userId) {
        if (playerX != null && playerX.getId() == userId) return 'X';
        if (playerO != null && playerO.getId() == userId) return 'O';
        return 0;
    }
}
