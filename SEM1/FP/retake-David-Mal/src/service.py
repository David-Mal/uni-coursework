from random import randint, random, choice

from src.domain import Board


class Service:
    def __init__(self):
        self.current_direction = None
        self.board = Board()
        self.A_row = 0
        self.A_col = 0
        self.M_row = 0
        self.M_col = 0
        self.game_over = False
        self.nr_of_squares_moved = 0
        self.winner = None

    def display_board(self):
        print(self.board)

    def place_players(self):
        matrix = self.board.board
        random_A = randint(1, len(matrix) - 2)
        random_M = randint(1, len(matrix) - 2)
        matrix[random_A][1] = 2
        matrix[random_M][len(matrix[0]) - 2] = 3
        self.A_row = random_A
        self.A_col = 1
        self.M_row = random_M
        self.M_col = len(matrix[0]) - 2
        self.board.board = matrix

    def one_move_A(self, direction):
        if direction == 'w' and self.board.board[self.A_row - 1][self.A_col] == 0:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_row -= 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction

        elif direction == 'w' and self.board.board[self.A_row - 1][self.A_col] == 9:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_row -= 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction
            self.game_over = True
            self.winner = 'A'

        elif direction == 'w' and self.board.board[self.A_row - 1][self.A_col] == 3:
            self.game_over = True
            self.winner = 'M'


        elif direction == 'w' and self.board.board[self.A_row - 1][self.A_col] == 1:
            raise Exception("Invalid move")


        if direction == 's' and self.board.board[self.A_row + 1][self.A_col] == 0:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_row += 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction

        elif direction == 's' and self.board.board[self.A_row + 1][self.A_col] == 9:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_row += 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction
            self.game_over = True
            self.winner = 'A'

        elif direction == 's' and self.board.board[self.A_row + 1][self.A_col] == 3:
            self.game_over = True
            self.winner = 'M'

        elif direction == 's' and self.board.board[self.A_row + 1][self.A_col] == 1:
            raise Exception("Invalid move")


        if direction == 'a' and self.board.board[self.A_row][self.A_col - 1] == 0:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_col -= 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction

        elif direction == 'a' and self.board.board[self.A_row][self.A_col - 1] == 9:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_col -= 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction
            self.game_over = True
            self.winner = 'A'

        elif direction == 'a' and self.board.board[self.A_row][self.A_col - 1] == 1:
            self.game_over = True
            self.winner = 'M'

        elif direction == 'a' and self.board.board[self.A_row][self.A_col - 1] == 1:
            raise Exception("Invalid move")


        if direction == 'd' and self.board.board[self.A_row][self.A_col + 1] == 0:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_col += 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction

        elif direction == 'd' and self.board.board[self.A_row][self.A_col + 1] == 9:
            self.board.board[self.A_row][self.A_col] = 0
            self.A_col += 1
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = 1
            self.current_direction = direction
            self.game_over = True
            self.winner = 'A'

        elif direction == 'd' and self.board.board[self.A_row][self.A_col + 1] == 3:
            self.game_over = True
            self.winner = 'M'

        elif direction == 'd' and self.board.board[self.A_row][self.A_col + 1] == 1:
            raise Exception("Invalid move")

    def multiple_moves_A(self, cells):
        if self.current_direction == 'w':
            for i in range(1, cells + 1):
                if self.board.board[self.A_row - i][self.A_col] == 1:
                    raise Exception("Invalid move")
                elif self.board.board[self.A_row - i][self.A_col] == 3:
                    self.game_over = True
                    self.winner = 'M'

                elif self.board.board[self.A_row - i][self.A_col] == 9:
                    self.game_over = True
                    self.winner = 'A'

            self.board.board[self.A_row][self.A_col] = 0
            self.A_row -= cells
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = cells

        elif self.current_direction == 's':
            for i in range(1, cells + 1):
                if self.board.board[self.A_row + i][self.A_col] == 1:
                    raise Exception("Invalid move")
                elif self.board.board[self.A_row + i][self.A_col] == 3:
                    self.game_over = True
                    self.winner = 'M'
                elif self.board.board[self.A_row + i][self.A_col] == 9:
                    self.game_over = True
                    self.winner = 'A'

            self.board.board[self.A_row][self.A_col] = 0
            self.A_row += cells
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = cells

        elif self.current_direction == 'a':
            for i in range(1, cells + 1):
                if self.board.board[self.A_row][self.A_col - i] != 0:
                    raise Exception("Invalid move")
                elif self.board.board[self.A_row][self.A_col - i] == 3:
                    self.game_over = True
                    self.winner = 'M'
                elif self.board.board[self.A_row][self.A_col - i] == 9:
                    self.game_over = True
                    self.winner = 'A'

            self.board.board[self.A_row][self.A_col] = 0
            self.A_col -= cells
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = cells

        elif self.current_direction == 'd':
            for i in range(1, cells + 1):
                if self.board.board[self.A_row][self.A_col + i] == 1:
                    raise Exception("Invalid move")
                elif self.board.board[self.A_row][self.A_col + i] == 3:
                    self.game_over = True
                    self.winner = 'M'
                elif self.board.board[self.A_row][self.A_col + i] == 9:
                    self.game_over = True
                    self.winner = 'A'
            self.board.board[self.A_row][self.A_col] = 0
            self.A_col += cells
            self.board.board[self.A_row][self.A_col] = 2
            self.nr_of_squares_moved = cells

    def move_minotaur(self):

        directions = ['w', 's', 'a', 'd']
        m_dir = choice(directions)

        while self.nr_of_squares_moved > 0 and not self.game_over:

            if self.A_row == self.M_row:
                if self.A_col < self.M_col:
                    m_dir = 'a'
                elif self.A_col > self.M_col:
                    m_dir = 'd'
                else:
                    self.game_over = True


            elif self.A_col == self.M_col:
                if self.A_row < self.M_row:
                    m_dir = 'w'
                elif self.A_row > self.M_row:
                    m_dir = 's'
                else:
                    self.game_over = True


            if m_dir == 'w':
                if self.board.board[self.M_row - 1][self.M_col] == 0:
                    self.board.board[self.M_row][self.M_col] = 0
                    self.M_row -= 1
                    self.board.board[self.M_row][self.M_col] = 3
                    self.nr_of_squares_moved -= 1
                else:
                    directions = ['w', 's', 'a', 'd']
                    m_dir = choice(directions)

            elif m_dir == 's':
                if self.board.board[self.M_row + 1][self.M_col] == 0:
                    self.board.board[self.M_row][self.M_col] = 0
                    self.M_row += 1
                    self.board.board[self.M_row][self.M_col] = 3
                    self.nr_of_squares_moved -= 1
                else:
                    directions = ['w', 's', 'a', 'd']
                    m_dir = choice(directions)

            elif m_dir == 'a':
                if self.board.board[self.M_row][self.M_col - 1] == 0:
                    self.board.board[self.M_row][self.M_col] = 0
                    self.M_col -= 1
                    self.board.board[self.M_row][self.M_col] = 3
                    self.nr_of_squares_moved -= 1
                else:
                    directions = ['w', 's', 'a', 'd']
                    m_dir = choice(directions)

            elif m_dir == 'd':
                if self.board.board[self.M_row][self.M_col + 1] == 0:
                    self.board.board[self.M_row][self.M_col] = 0
                    self.M_col += 1
                    self.board.board[self.M_row][self.M_col] = 3
                    self.nr_of_squares_moved -= 1
                else:
                    directions = ['w', 's', 'a', 'd']
                    m_dir = choice(directions)

    def check_game_over(self):
        if self.game_over:
            print("Game Over")
            print (f"winner is {self.winner}")






s = Service()
s.place_players()