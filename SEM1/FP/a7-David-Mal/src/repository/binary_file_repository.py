import os
import pickle
from src.domain.domain import Domain
from src.repository.memory_repository import MemoryRepository

class BinaryFileRepository(MemoryRepository):
    def __init__(self, file_path: str):
        super().__init__()
        self._file_path = file_path
        self._load_numbers()

    def _load_numbers(self):
        """
        Loads the numbers from the binary file.
        """
        if not os.path.exists(self._file_path):
            return  # No file, nothing to load

        with open(self._file_path, "rb") as file:
            try:
                self.numbers = pickle.load(file)  # Load the list of Domain objects
            except EOFError:
                self.numbers = []  # If the file is empty, initialize an empty list

    def _save_numbers(self):
        """
        Saves the numbers to the binary file.
        """
        with open(self._file_path, "wb") as file:
            pickle.dump(self.numbers, file)  # Serialize and write the list of Domain objects

    def get_numbers(self):
        """
        Returns the list of complex numbers stored in the repository.
        """
        return super().get_numbers()

    def add_number(self, number):
        """
        Adds a number to the list and saves it to the binary file.
        """
        super().add_number(number)
        self._save_numbers()  # Save the list of numbers to the binary file

    def filter_numbers(self, start, end):
        """
        Filters the list of numbers based on the provided indices and saves the filtered list to the binary file.
        """
        super().filter_numbers(start, end)
        self._save_numbers()  # Save the filtered list to the binary file
