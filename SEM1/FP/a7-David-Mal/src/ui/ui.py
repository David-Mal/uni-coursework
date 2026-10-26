from src.services.services import Services
from src.repository.memory_repository import MemoryRepository
from src.repository.text_file_repository import TextFileRepository
from src.domain.domain import Domain


class UI:
    def __init__(self, repository):
        self.__repository = repository

    @staticmethod
    def choose_repo():
        """
        method that lets the user chose the repository to be used in the application
        :return:
        """
        print("Choose Repo type:")
        print("1.Memory Repository")
        print("2.Text File Repository")
        choice = input("Enter your choice: ")
        if choice == '1':
            repo = MemoryRepository()  # this is the memory repository
            return repo
        elif choice == '2':
            repo = TextFileRepository()  # this is the text file repository
            return repo
        else:
            print("choice is not valid")

    def menu(self):
        self.__repository.randomgen()  # here we generate 10 random instances
        while True:
            print("1 - ADD")
            print("2 - DISPLAY")
            print("3 - FILTER")
            print("4 - UNDO")
            print("5 - EXIT")
            choice = input("Enter your choice: ")

            if choice == '1':
                while True:
                    try:
                        a = int(input("a: "))
                        b = int(input("b: "))
                        break
                    except ValueError:
                        print("Values should be integers!")
                number = str(Domain(a, b))
                self.__repository.add_number(number)  # add the number red to the list
                self.__repository.add_undo()  # add the current state of the list to the stack

            elif choice == '2':
                self.__repository.display()  # display the list of numbers

            elif choice == '3':
                while True:
                    try:
                        start = int(input("Input the starting point: "))
                        end = int(input("Input the ending point: "))
                        if start > end:
                            print("The start value can't be bigger than the end value")
                        else:
                            break
                    except ValueError:
                        print("Values should be integers!")

                self.__repository.filter(start, end)  # filter the list of numbers
                self.__repository.add_undo()  # add the current state of the list to the stack

            elif choice == '4':
                self.__repository.undo()  # call the undo function

            elif choice == '5':
                exit()  # exit the program

            else:
                print("Invalid choice!")


# function for tests
def Test():
    number = Domain(7, 9)
    assert (str(number) == '7 + 9i')
    assert (number.get_real() == 7)
    assert (number.get_imag() == 9)
