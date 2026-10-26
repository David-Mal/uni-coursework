from service import ConnectFourService
from domain import ConnectFour
class Ui:
    def __init__(self):
        self.service = None

    def display_board(self, board, winning_cells):
        for row in range(ConnectFour.ROWS):
            for col in range(ConnectFour.COLS):
                cell = board[row][col]
                if (row, col) in winning_cells:
                    print(f"\033[91m{cell}\033[0m", end="|")  # Red color for winning cells
                else:
                    print(cell, end="|")
            print()
        print("-" * (2 * ConnectFour.COLS - 1))
        print(" ".join(map(str, range(ConnectFour.COLS))))


    def start_game(self):
        print("Welcome to Connect Four!")
        mode = input("Do you want to play against the computer? (yes/no): ").strip().lower()
        self.service = ConnectFourService(against_computer=(mode == "yes"))

        while True:
            self.display_board(self.service.get_board(), self.service.get_winning_cells())
            if self.service.game.current_player == "X" or not self.service.against_computer:
                try:
                    col = int(input(f"Player {self.service.game.current_player}, choose a column (0-{ConnectFour.COLS - 1}): "))
                except ValueError:
                    print("Please enter a valid number.")
                    continue
            else:
                print("Computer is making its move...")
                col = None  # The service will handle the computer's move

            result = self.service.play_turn(col)
            if result:
                self.display_board(self.service.get_board(), self.service.get_winning_cells())
                print(result)
                if "wins" in result or "draw" in result:
                    break