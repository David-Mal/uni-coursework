import random
from src.domain.domain import Domain
from pdoc import pdoc
class MemoryRepository:
    """
        MemoryRepository class for managing in-memory storage of complex numbers.
    """
    def __init__(self):
        self.numbers=[] # the list of complex numbers
        self.undo =[]   # the stack that contains the previous states of the list
        self.add_undo()

    def add_number(self, number):
        """
        method that adds a number to the self.numbers list
        :param number: the complex number to be added, created in the domain class
        :return: none
        """
        self.numbers.append(number)

    def get_numbers(self):
        """
        method that returns the list self.numbers
        :return: none
        """
        return self.numbers

    def randomgen(self):
        """
        method that generates 10 random complex numbers and add them to the list
        :return: none
        """
        l=[]
        for i in range(10):
            a=random.randint(1,100)
            b=random.randint(1,100)
            number=str(Domain(a,b))
            l.append(number)
        self.numbers=l
        self.undo.append(l[:])

    def filter_numbers(self, start, end):
        """
        method that filters the self.numbers list, so that it only keeps the complex numbers with indexes from [start, end]
        :param start: the start index
        :param end: the end index
        :return: none
        """
        self.numbers[:] = self.numbers[(start-1):end]

    # adds the list of numbers to the undo stack

    def add_undo(self):
        """
        method that adds the current state of the list to the undo stack
        :return: none
        """
        self.undo.append(self.numbers[:])

    # adds the last item from the undo stack to the main list
    def undo1(self):
        """
        method that performs the undo operation
        :return: none
        """
        if len(self.undo) > 2:
            """
            self.undo.pop()
            self.numbers = []
            self.numbers = self.undo[len(self.undo)-1]
            """
            self.undo.pop()
            self.numbers[:] = self.undo[len(self.undo) - 1]

        else:
            print("Nothing to undo")

if __name__ == "__main__":
    f = open("repo.html", "wt")
    f.write(pdoc("memory_repository.py"))
    f.write(pdoc("text_file_repository.py"))
    f.write(pdoc("binary_file_repository.py"))
    f.close()
