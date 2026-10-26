from src.service import Service


class UI:
    def __init__(self):
        self.s = Service()


    def run(self):
        print("Welcome to the game!")

        # PART 1 - place random A and M
        self.s.place_players()
        self.s.display_board()


        # PART 2 - moving phase
        
        while not self.s.game_over:
            self.s.check_game_over()
            move = input("Chose your move: ")
            if move in ["w", "s", "a", "d"]:
                try:
                    self.s.one_move_A(move)
                    self.s.check_game_over()
                    self.s.move_minotaur()
                    self.s.display_board()
                    self.s.check_game_over()
                except Exception as e:
                    print(e)
            elif move == "move" and self.s.current_direction is not None:
                try:
                    self.s.multiple_moves_A(1)
                    self.s.check_game_over()
                    self.s.move_minotaur()
                    self.s.display_board()
                    self.s.check_game_over()
                except Exception as e:
                    print(e)

            elif self.s.current_direction is not None:
                    command, n = move.split(' ')
                    if command == "move":
                        try:
                            n = int(n)
                            if n < 0:
                                raise ValueError
                            self.s.multiple_moves_A(n)
                            self.s.check_game_over()
                            self.s.move_minotaur()
                            self.s.display_board()
                            self.s.check_game_over()
                        except ValueError as e:
                            print(e)
                        except Exception as e:
                            print(e)

                    else:
                        print("That's not a valid move!")
            else:
                print("Pick a valid move, can't use move instruction before w,a,s,d")
        if self.s.game_over:
            print("Game over!")

ui = UI()
ui.run()