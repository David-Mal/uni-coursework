import os
from src.domain.domain import Domain
from src.repository.memory_repository import MemoryRepository

class TextFileRepository(MemoryRepository):
    def __init__(self, file_path: str):
        super().__init__()
        self._file_path = file_path
        self._load_numbers()

    def _load_numbers(self):
        """
        Loads the numbers from the file.
        """
        if not os.path.exists(self._file_path):
            return

        with open(self._file_path, "r") as file:
            for line in file:
                line = line.strip()
                if line == "":
                    continue

                real_part, imaginary_part = line.split(" + ")
                imaginary_part = imaginary_part.rstrip("i")  # Remove the 'i' at the end
                self.numbers.append(
                    Domain(int(real_part), int(imaginary_part))
                )

    def _save_numbers(self):
        """
        Saves the numbers to the file.
        """
        with open(self._file_path, "w") as file:
            for number in self.numbers:
                file.write(str(number) + '\n')

    def get_numbers(self):
        return super().get_numbers()

    def add_number(self, number): # redundant
        super().add_number(number)
        self._save_numbers()

    def filter_numbers(self, start, end):
        super().filter_numbers(start, end)  # Filters the list in memory
        self._save_numbers()  # Saves the filtered list to the text file