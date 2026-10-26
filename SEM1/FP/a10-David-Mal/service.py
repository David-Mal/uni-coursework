from domain import ConnectFour
import random
class ConnectFourService:
    def __init__(self, against_computer=False):
        self.game = ConnectFour()
        self.against_computer = against_computer

    def play_turn(self, col=None):
        """
        Executes a turn for the current player.
        - If the current player is "O" and the game is against the computer, generates a computer move.
        - Attempts to drop a piece in the specified or generated column.
        - Checks for a win or draw condition after the move.
        - Switches to the next player if no win or draw occurs.
        :param col: (int) The column where the current player wants to drop their piece (optional for computer).
        :return:
            - (str): A message indicating the result of the turn:
                - "Invalid move. Try again." if the move is invalid.
                - "Player X wins!" or "Player O wins!" if a win is detected.
                - "It's a draw!" if the board is full.
            - (None): If the turn is completed without a win or draw.
        """
        if self.game.current_player == "O" and self.against_computer:
            col = self.computer_move()

        move = self.game.drop_piece(col)
        if not move:
            return "Invalid move. Try again."

        row, col = move
        if self.game.check_winner(row, col):
            winner = self.game.current_player
            # self.game = ConnectFour()  # Reset game after win
            return f"\033[93mPlayer\033[0m \033[91m{winner}\033[0m \033[93mwins!\033[0m"

        if self.game.is_full():
            self.game = ConnectFour()  # Reset game after draw
            return "It's a draw!"

        self.game.switch_player()
        return None

    def computer_move(self):
        """
        Generates a move for the computer.
        - Identifies all valid columns where a piece can be dropped.
        - Randomly selects one of the valid columns.
        :return: (int) The column index selected by the computer.
        """
        valid_columns = [col for col in range(self.game.COLS) if self.game.board[0][col] == " "]
        return random.choice(valid_columns)

    def get_board(self):
        return self.game.board

    def get_winning_cells(self):
        return self.game.winning_cells