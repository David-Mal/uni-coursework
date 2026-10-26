class ConnectFour:
    ROWS = 6
    COLS = 7

    def __init__(self):
        """
        Initializes a new Connect Four game.
        - Creates an empty game board with dimensions ROWS x COLS.
        - Sets the current player to "X".
        - Resets the list of winning cells.
        :return: None
        """
        self.board = [[" " for _ in range(self.COLS)] for _ in range(self.ROWS)]
        self.current_player = "X"
        self.winning_cells = []  # Store coordinates of the winning cells

    def drop_piece(self, col):
        """
        Places the current player's piece in the specified column.
        - Checks if the column is valid and not full.
        - Places the piece in the lowest available row of the column.
        :param col: (int) The column where the player wants to drop their piece (0-indexed).
        :return:
        - (tuple[int, int]): The (row, col) position where the piece was successfully placed.
        - (bool): False if the move is invalid (e.g., column is full or out of bounds).
        """
        if col < 0 or col >= self.COLS or self.board[0][col] != " ":
            return False  # Invalid move

        # Place piece in the lowest available row
        for row in range(self.ROWS - 1, -1, -1):
            if self.board[row][col] == " ":
                self.board[row][col] = self.current_player
                return row, col
        return False

    def check_winner(self, row, col):
        """
        Checks if the last move resulted in a win for the current player.
        - Evaluates horizontal, vertical, and diagonal directions for 4 consecutive pieces.
        - If a win is detected, stores the winning cells' coordinates in `winning_cells`.
        :param row: (int) The row index of the last move.
        :param col: (int) The column index of the last move.
        :return: (bool) True if the move resulted in a win, False otherwise.
        """
        def count_consecutive(dx, dy):
            cells = []
            r, c = row, col
            while 0 <= r < self.ROWS and 0 <= c < self.COLS and self.board[r][c] == self.current_player:
                cells.append((r, c))
                r += dx
                c += dy
            return cells

        # Check all directions (horizontal, vertical, diagonal)
        directions = [(0, 1), (1, 0), (1, 1), (1, -1)]
        for dx, dy in directions:
            forward = count_consecutive(dx, dy)
            backward = count_consecutive(-dx, -dy)
            combined = forward + backward[1:]  # Avoid double-counting the current cell

            if len(combined) >= 4:
                self.winning_cells = combined
                return True
        return False


    def is_full(self):
        """
        Checks if the game board is full (i.e., no more valid moves).
        - Iterates through the top row to check for empty spaces.
        :return: (bool) True if the board is full, False otherwise.
        """
        return all(self.board[0][col] != " " for col in range(self.COLS))

    def switch_player(self):
        """
        Switches the current player to the other player.
        - If the current player is "X", changes to "O".
        - If the current player is "O", changes to "X".
        :return: None
        """
        self.current_player = "O" if self.current_player == "X" else "X"
