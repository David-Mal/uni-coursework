from src.domain.book import Book
from src.repository.repository_exception import RepositoryException


class BookService:
    def __init__(self, book_repo, rental_repo):
        self._book_repo = book_repo
        self._rental_repo = rental_repo

    def add_book(self, book_id, title, author):
        book = Book(book_id, title, author)
        try:
            self._book_repo.add(book)
        except RepositoryException as repository_exception:
            print(f"Book with id {book_id} already exists!")

    def remove_book(self, book_id):
       try:
            # delete all the rentals that have the book with the given id
            rentals = self._rental_repo.get_all_rentals()
            for rental in rentals:
                if rental.book_id == book_id:
                    self._rental_repo.remove_rental(rental.id)

            # delete the book
            self._book_repo.remove(book_id)

       except RepositoryException as repository_exception:
           print(f"Book with id {book_id} does not exist!")

    def get_all_books(self):
        return self._book_repo.get_all()

    def update_book(self, book_id, title, author):
        book = Book(book_id, title, author)
        try:
            self._book_repo.update(book)
        except RepositoryException as repository_exception:
            print(f"Book with id {book_id} does not exist!")


    def search_book(self, field_to_search_for):
            return self._book_repo.search(field_to_search_for)