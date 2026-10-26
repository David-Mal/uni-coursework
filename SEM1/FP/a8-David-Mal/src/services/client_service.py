import random
from src.domain.client import Client
from src.repository.repository import Repository
from src.repository.repository_exception import RepositoryException


class ClientService:
    def __init__(self, client_repository, rental_service):
        self.__client_repository = client_repository
        self.__rental_service = rental_service

    def add_client(self, client_id, name):
        client = Client(client_id, name)
        try:
            self.__client_repository.add(client)
        except RepositoryException as repository_exception:
            print(f"Client with id {client_id} already exists!")

    def remove_client(self, client_id):
        try:
            self.__client_repository.remove(client_id)
            rentals = self.__rental_service.get_all_rentals()

            for rental in rentals:
                if rental.client_id == client_id:
                    self.__rental_service.remove_rental(rental.id)

        except RepositoryException as repository_exception:
            print(f"Client with id {client_id} does not exist!")

    def update_client(self, client_id, name):
        client = Client(client_id, name)
        try:
            self.__client_repository.update(client)
        except RepositoryException as repository_exception:
            print(f"Client with id {client_id} does not exist!")

    def get_all_clients(self):
        return self.__client_repository.get_all()

    def search_client(self, field_to_search_for):
        return self.__client_repository.search(field_to_search_for)