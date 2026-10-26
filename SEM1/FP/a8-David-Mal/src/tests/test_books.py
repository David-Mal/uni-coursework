import unittest
from src.repository.rental_repository import RentalRepository
from src.repository.repository import Repository
from src.services.book_service import BookService
from src.services.rental_service import RentalService

class TestBookService(unittest.TestCase):
    def setUp(self):
        self.rental_repository = RentalRepository()
        self.book_repository = Repository()
        self.rental_service = RentalService(self.rental_repository)
        self.book_service = BookService(self.book_repository, self.rental_service)

    def test_add_book_success(self):
        book_id = 10
        title = "Diego Maradona"
        author = "Pele"
        self.book_service.add_book(book_id, title, author)
        self.assertEqual(len(self.book_service.get_all_books()), 1)

    def test_remove_book_success(self):
        book_id = 10
        title = "Diego Maradona"
        author = "Pele"
        self.book_service.add_book(book_id, title, author)
        self.book_service.remove_book(book_id)
        self.assertEqual(len(self.book_service.get_all_books()), 0)

    def test_update_book_success(self):
        book_id = 1
        title = "Diego Maradona"
        author = "Pele"
        self.book_service.add_book(book_id, title, author)
        new_title = "El Mejor"
        self.book_service.update_book(book_id, new_title, author)
        updated_book = self.book_service.get_all_books()[0]
        self.assertEqual(updated_book.title, new_title)

    def test_get_all_books(self):
        self.assertEqual(len(self.book_service.get_all_books()), 0)