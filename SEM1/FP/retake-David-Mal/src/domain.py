from texttable import Texttable


class Board:
    def __init__(self):
        self.filename = 'labyrinth_simple.txt'
        self.board = self.read_board(self.filename)

    def read_board(self, filename):
        board = []
        try:
            f = open(filename, 'r').read().split('\n')
            for line in f:
                if line == "":
                    continue
                row = []
                for cell in line:
                    row.append(int(cell))
                board.append(row)
            return board
        except IOError as e:
            raise e
    def __str__(self):
        tt = Texttable()
        for line in self.board:
            row = []
            for i in line:
                if i == 1:
                    row.append('x')
                elif i == 0:
                    row.append(' ')
                elif i == 9:
                    row.append('-')
                elif i == 2:
                    row.append('A') # Ariadne
                elif i == 3:
                    row.append('M') # Minotaur
            tt.add_row(row)
        return tt.draw()

# board = Board()
# print(board)
