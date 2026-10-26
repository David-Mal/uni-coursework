from src.domain.rental import Rental
from src.repository.repository_exception import RepositoryException


class RentalService:
    def __init__(self, rental_repository):
        self.__rental_repository = rental_repository

    def add_rental(
        self,
        rental_id: int,
        book_id: int,
        client_id: int,
        rented_date,
        books: list,
        clients: list,
        returned_date=None,
    ):
        """
        Adds a rental to the repository.

        :param rental_id: The rental ID.
        :param book_id: The book ID.
        :param client_id: The client ID.
        :param rented_date: The rented date.
        :param books: The books list
        :param clients: The clients list

        """

        # Create a new Rental object
        rental = Rental(
            rental_id, book_id, client_id, rented_date, returned_date or None
        )
        # Add the rental using the repository's add method
        self.__rental_repository.add(rental, books, clients)

    def remove_rental(self, rental_id):
        rental = self.__rental_repository.get_rental_by_id(rental_id)
        self.__rental_repository.remove(rental_id)

    def update_rental(self, rental_id, returned_date):
        try:
            rental = self.get_rental_by_id(rental_id)
        except RepositoryException as repository_exception:
            print(repository_exception)

        rental = Rental(
            rental.id,
            rental.book_id,
            rental.client_id,
            rental.rented_date,
            returned_date,
        )

        self.__rental_repository.update(rental)

    def get_all_rentals(self):
        return self.__rental_repository.get_all()

    def get_rental_by_id(self, rental_id):
        return self.__rental_repository.get_rental_by_id(rental_id)