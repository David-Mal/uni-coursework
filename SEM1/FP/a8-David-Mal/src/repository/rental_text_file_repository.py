import os
from datetime import datetime

from src.domain.rental import Rental
from src.repository.rental_repository import RentalRepository


class RentalTextFileRepository(RentalRepository):
    def __init__(self, file_name):
        super().__init__()
        self.__file_name = file_name
        self.__load_data()

    def __load_data(self):
        if not os.path.exists(self.__file_name):
            return

        with open(self.__file_name, "r") as file:
            for line in file:
                if line == "\n":
                    continue
                line = line.strip()
                rental_id, book_id, client_id, rented_date, returned_date = line.split(
                    ";"
                )

                # self._rentals[rental_id] = Rental(
                # rental_id, book_id, client_id, rented_date, returned_date
                # )
                if rented_date != '':
                    rented_date = datetime.strptime(rented_date, "%Y-%m-%d").date()
                if returned_date != 'None':
                    returned_date = datetime.strptime(returned_date, "%Y-%m-%d").date()

                self.add(
                    Rental(rental_id, int(book_id), client_id, rented_date, returned_date),
                    None,
                    None,
                )

    def __save_data(self):
        with open(self.__file_name, "w") as file:
            for rental in self.get_all():
                line = (
                    str(rental.id)
                    + ";"
                    + str(rental.book_id)
                    + ";"
                    + str(rental.client_id)
                    + ";"
                    + str(rental.rented_date)
                    + ";"
                    + str(rental.returned_date)
                    + "\n"
                )

                file.write(line)

    def add(self, rental, books, clients, undo_redo=False):
        super().add(rental, books, clients)
        self.__save_data()

    def remove(self, rental_id):
        super().remove(rental_id)
        self.__save_data()

    def update(self, rental):
        super().update(rental)
        self.__save_data()

    def get_all(self):
        return super().get_all()

    def get_rental_by_id(self, rental_id):
        return super().get_rental_by_id(rental_id)