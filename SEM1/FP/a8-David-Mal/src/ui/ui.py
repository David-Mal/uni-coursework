from faker import Faker

from src.repository.binary_file_repository import BinaryFileRepository
from src.repository.rental_binary_file_repository import RentalBinaryFileRepository
from src.repository.rental_repository import RentalRepository
from src.repository.rental_text_file_repository import RentalTextFileRepository
from src.repository.repository import Repository
from src.repository.repository_exception import RepositoryException
from src.repository.text_file_repository import TextFileRepository
from src.services.book_service import BookService
from src.services.client_service import ClientService
from src.services.rental_service import RentalService
from src.helpers.helpers import *

class UI:
    def __init__(self):
        try:
            (
                self.__book_repository,
                self.__client_repository,
                self.__rental_repository,
            ) = self.__validate_settings_file("settings.properties")
        except RepositoryException as repository_exception:
            print(repository_exception)
            exit(1)

        self.__rental_service = RentalService(self.__rental_repository)

        self.__book_service = BookService(self.__book_repository, self.__rental_service)
        self.__client_service = ClientService(self.__client_repository, self.__rental_service)

    def __print_menu(self):
        print("1. Book operations")
        print("2. Client operations")
        print("3. Rental operations")
        print("4. Search operations")
        print("0. Exit")

    def __print_book_menu(self):
        print("1. Add book")
        print("2. Remove book")
        print("3. Update book")
        print("4. List all books")
        print("0. Back")

    def __print_client_menu(self):
        print("1. Add client")
        print("2. Remove client")
        print("3. Update client")
        print("4. List all clients")
        print("0. Back")

    def __print_rental_menu(self):
        print("1. Rent book")
        print("2. Return book")
        print("3. List all rentals")
        print("0. Back")

    def __print_search_menu(self):
        print("1. Search books")
        print("2. Search clients")
        print("0. Back")

    def __validate_settings_file(self, file_name):
        """
        Determines if the given file_name is a valid settings file.
        """
        with open(file_name, "r") as settings_file:
            settings = settings_file.readlines()

        settings = [setting.strip().split("=")[1].strip() for setting in settings]

        if settings[0] == "inmemory":
            return Repository(), Repository(), RentalRepository()
        elif settings[0] == "binaryfiles":
            return (
                BinaryFileRepository(settings[1]),
                BinaryFileRepository(settings[2]),
                RentalBinaryFileRepository(settings[3]),
            )
        elif settings[0] == "textfiles":
            return (
                TextFileRepository(settings[1]),
                TextFileRepository(settings[2]),
                RentalTextFileRepository(settings[3]),
            )
        else:
            raise RepositoryException("Invalid repository type!")

    def generate_data(self):

        fake = Faker()

        if not self.__book_service.get_all_books():
            for i in range(20):
                book_id = fake.unique.random_int(min=1, max=1000)
                title = fake.sentence(nb_words=3)
                author = fake.name()
                self.__book_service.add_book(book_id, title, author)

        if not self.__client_service.get_all_clients():
            for i in range(20):
                client_id = fake.unique.random_int(min=1, max=1000)
                name = fake.name()
                self.__client_service.add_client(client_id, name)

        if not self.__rental_service.get_all_rentals():
            books = self.__book_service.get_all_books()
            clients = self.__client_service.get_all_clients()

            for i in range(20):
                book = books[i]
                client = fake.random_element(clients)
                rental_id = fake.unique.random_int(min=1, max=1000)
                rental_date = fake.date_this_year()
                try:
                    self.__rental_service.add_rental(rental_id, book.id, client.id, rental_date, books, clients)
                except Exception as e:
                    print(f"Failed to add rental: {e}")
            print("Generated 20 fake rentals.")

    def _main_loop(self):
        BOOK_OPERATIONS = 1
        CLIENT_OPERATIONS = 2
        RENTAL_OPERATIONS = 3
        SEARCH_OPERATIONS = 4
        EXIT = 0

        while True:
            print()
            self.__print_menu()
            print()
            try:
                user_command = int(input("Enter command: "))
            except ValueError:
                continue

            if user_command == BOOK_OPERATIONS:
                print()
                self.__print_book_menu()
                print()

                ADD_BOOK = 1
                REMOVE_BOOK = 2
                UPDATE_BOOK = 3
                LIST_ALL_BOOKS = 4
                BACK = 0
                try:
                    user_command = int(input("Enter command: "))
                except ValueError:
                    continue

                if user_command == ADD_BOOK:
                    try:
                        book_id = int(input("Enter book ID: "))
                        title = input("Enter title: ")
                        author = input("Enter author: ")

                        self.__book_service.add_book(book_id, title, author)
                    except ValueError:
                        continue

                elif user_command == REMOVE_BOOK:
                    try:
                        book_id = int(input("Enter book ID: "))

                        self.__book_service.remove_book(book_id)
                    except ValueError:
                        continue

                elif user_command == UPDATE_BOOK:
                    try:
                        book_id = int(input("Enter book ID: "))
                        title = input("Enter title: ")
                        author = input("Enter author: ")

                        self.__book_service.update_book(book_id, title, author)
                    except ValueError:
                        continue

                elif user_command == LIST_ALL_BOOKS:
                    books = self.__book_service.get_all_books()
                    if len(books) == 0:
                        print("\nNo books!\n")
                        continue

                    print("\nAll books:\n")
                    for book in books:
                        print(book)
                    print()

                elif user_command == BACK:
                    continue

            elif user_command == CLIENT_OPERATIONS:
                print()
                self.__print_client_menu()
                print()

                ADD_CLIENT = 1
                REMOVE_CLIENT = 2
                UPDATE_CLIENT = 3
                LIST_ALL_CLIENTS = 4
                BACK = 0
                try:
                    user_command = int(input("Enter command: "))
                except ValueError:
                    continue

                if user_command == ADD_CLIENT:
                    try:
                        client_id = int(input("Enter client ID: "))
                        name = input("Enter name: ")

                        self.__client_service.add_client(client_id, name)
                    except ValueError:
                        continue

                elif user_command == REMOVE_CLIENT:
                    try:
                        client_id = int(input("Enter client ID: "))

                        self.__client_service.remove_client(client_id)
                    except ValueError:
                        continue

                elif user_command == UPDATE_CLIENT:
                    try:
                        client_id = int(input("Enter client ID: "))
                        name = input("Enter name: ")

                        self.__client_service.update_client(client_id, name)
                    except RepositoryException as error:
                        print(error)
                        continue

                elif user_command == LIST_ALL_CLIENTS:
                    clients = self.__client_service.get_all_clients()
                    if len(clients) == 0:
                        print("\nNo clients!\n")
                        continue

                    print("\nAll clients:\n")
                    for client in clients:
                        print(client)
                    print()

                elif user_command == BACK:
                    continue

            elif user_command == RENTAL_OPERATIONS:
                print()
                self.__print_rental_menu()
                print()

                RENT_BOOK = 1
                RETURN_BOOK = 2
                LIST_ALL_RENTALS = 3
                BACK = 0

                try:
                    user_command = int(input("Enter command: "))
                except ValueError:
                    continue

                if user_command == RENT_BOOK:
                    try:
                        rental_id = int(input("Enter rental ID: "))
                        book_id = int(input("Enter book ID: "))
                        client_id = int(input("Enter client ID: "))
                        rented_date = read_date("Enter rented date: ")
                    except ValueError:
                        continue

                    books = self.__book_service.get_all_books()
                    clients = self.__client_service.get_all_clients()

                    try:
                        self.__rental_service.add_rental(
                            rental_id,
                            book_id,
                            client_id,
                            rented_date,
                            books,
                            clients,
                            None,
                        )
                    except RepositoryException as error:
                        print(error)

                elif user_command == RETURN_BOOK:
                    try:
                        rental_id = input("Enter rental ID: ")
                        returned_date = read_date("Enter returned date: ")
                    except ValueError:
                        continue

                    try:
                        self.__rental_service.update_rental(rental_id, returned_date)
                    except RepositoryException as error:
                        print(error)

                elif user_command == LIST_ALL_RENTALS:
                    rentals = self.__rental_service.get_all_rentals()
                    if len(rentals) == 0:
                        print("\nNo rentals!\n")
                        continue

                    print("\nAll rentals:\n")
                    for rental in rentals:
                        print(rental)
                    print()

                elif user_command == BACK:
                    continue

            elif user_command == SEARCH_OPERATIONS:
                print()
                self.__print_search_menu()
                print()

                SEARCH_BOOKS = 1
                SEARCH_CLIENTS = 2
                BACK = 0

                try:
                    user_command = int(input("Enter command: "))
                except ValueError:
                    continue

                if user_command == SEARCH_BOOKS:
                    field_to_search_for = input("Enter field to search for in books: ")
                    books = self.__book_service.search_book(field_to_search_for)
                    if len(books) == 0:
                        print("\nNo books!\n")
                        continue

                    print("\nAll books:\n")
                    for book in books:
                        print(book)
                    print()

                elif user_command == SEARCH_CLIENTS:
                    field_to_search_for = input("Enter field to search for in clients: ")
                    clients = self.__client_service.search_client(field_to_search_for)
                    if len(clients) == 0:
                        print("\nNo clients!\n")
                        continue

                    print("\nAll clients:\n")
                    for client in clients:
                        print(client)
                    print()

                elif user_command == BACK:
                    continue
            elif user_command == EXIT:
                break
            else:
                print("\nInvalid command!\n")