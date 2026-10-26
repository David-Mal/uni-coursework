import unittest
from src.repository.rental_repository import RentalRepository
from src.repository.repository import Repository
from src.services.book_service import BookService
from src.services.client_service import ClientService
from src.services.rental_service import RentalService


class TestRentalService(unittest.TestCase):
    def setUp(self):
        self.rental_repository = RentalRepository()
        self.book_repository = Repository()
        self.client_repository = Repository()
        self.rental_service = RentalService(self.rental_repository)

        self.book_service = BookService(self.book_repository, self.rental_service)
        self.client_service = ClientService(self.client_repository, self.rental_service)

    def test_get_all_rentals(self):
        self.assertEqual(len(self.rental_service.get_all_rentals()), 0)

        self.book_service.add_book(20, "Lionel Messi", "Cristiano Ronaldo")
        self.client_service.add_client(20, "Lionel Messi")
        self.rental_service.add_rental(20, 20, 20, "2023-12-12", None, None)

        self.assertEqual(len(self.rental_service.get_all_rentals()), 1)

    def test_add_rental_success(self):
        rental_id = 20
        book_id = 10
        client_id = 10
        rented_date = "2023-12-12"
        self.book_service.add_book(20, "Lionel Messi", "Cristiano Ronaldo")
        self.rental_service.add_rental(
            rental_id, book_id, client_id, rented_date, None, None
        )
        self.assertEqual(len(self.rental_service.get_all_rentals()), 1)

    def test_update_rental_success(self):
        rental_id = 20
        book_id = 10
        client_id = 10
        rented_date = "2023-12-12"
        self.book_service.add_book(20, "Lionel Messi", "Cristiano Ronaldo")
        self.rental_service.add_rental(
            rental_id, book_id, client_id, rented_date, None, None
        )
        self.assertEqual(len(self.rental_service.get_all_rentals()), 1)

        returned_date = "2023-12-13"
        self.rental_service.update_rental(rental_id, returned_date)

        self.assertEqual(
            self.rental_service.get_all_rentals()[0].returned_date, returned_date
        )