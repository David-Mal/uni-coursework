import os
import json
from src.domain.domain import Domain
from src.repository.memory_repository import MemoryRepository

class JSONFileRepository(MemoryRepository):
    def __init__(self, file_path: str):
        super().__init__()
        self._file_path = file_path
        self._load_numbers()

    def _load_numbers(self):
        """
        Loads the numbers from the JSON file.
        """
        if not os.path.exists(self._file_path):
            return  # No file exists, nothing to load

        with open(self._file_path, "r") as file:
            try:
                data = json.load(file)  # Load the JSON data
                self.numbers = []  # Initialize the list of numbers
                for item in data:
                    # Ensure each item is a dictionary with real and imaginary parts
                    if isinstance(item, dict) and 'real_part' in item and 'imag_part' in item:
                        # Convert to Domain objects
                        self.numbers.append(Domain(item['real_part'], item['imag_part']))
                    else:
                        print(f"Invalid data in JSON: {item}")
            except json.JSONDecodeError:
                self.numbers = []  # If the file is empty or invalid JSON, initialize an empty list
                print("Error decoding JSON data.")

    def _save_numbers(self):
        """
        Saves the numbers to the JSON file.
        """
        # Convert the list of Domain objects to a list of dictionaries
        data = [
            {'real_part': number.get_real(), 'imag_part': number.get_imag()} for number in self.numbers
        ]
        with open(self._file_path, "w") as file:
            json.dump(data, file, indent=4)  # Serialize the data to JSON with indentation

    def get_numbers(self):
        """
        Returns the list of complex numbers stored in the repository.
        """
        return super().get_numbers()

    def add_number(self, number):
        """
        Adds a number to the list and saves it to the JSON file.
        """
        super().add_number(number)
        self._save_numbers()  # Save the updated list to the JSON file

    def filter_numbers(self, start, end):
        """
        Filters the list of numbers based on the provided indices and saves the filtered list to the JSON file.
        """
        super().filter_numbers(start, end)
        self._save_numbers()  # Save the filtered list to the JSON file
